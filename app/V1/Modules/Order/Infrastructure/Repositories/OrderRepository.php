<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Order\Domain\Enums\OrderStatus;
use App\V1\Modules\Order\Domain\Exceptions\InvalidOrderStateException;
use App\V1\Modules\Order\Domain\Exceptions\OrderNotFoundException;
use App\V1\Modules\Order\Domain\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Throwable;

class OrderRepository extends EloquentModelRepository
{
    public function model(): Order
    {
        return new Order();
    }

    public function moduleName(): string
    {
        return 'order';
    }

    /**
     * @return Builder<Order>
     */
    private function orderQuery(): Builder
    {
        return Order::query();
    }

    /**
     * @return Collection<int, Order>
     */
    public function getByCompany(Company $company): Collection
    {
        return $this->orderQuery()
            ->where('company_id', $company->id)
            ->with(['offer', 'inquiry', 'customer', 'owner', 'items'])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * @throws OrderNotFoundException|Throwable
     */
    public function findCompanyOrder(Company $company, string $orderId): Order
    {
        $order = $this->orderQuery()
            ->where('company_id', $company->id)
            ->where('id', $orderId)
            ->with(['offer', 'inquiry', 'customer', 'owner', 'items'])
            ->first();

        throw_if(! $order instanceof Order, OrderNotFoundException::class);

        return $order;
    }

    /**
     * @throws InvalidOrderStateException|OrderNotFoundException|Throwable
     */
    public function changeStatus(Company $company, Order $order, OrderStatus $nextStatus): Order
    {
        $currentStatus = OrderStatus::from($order->status);

        throw_if(! $this->canTransition($currentStatus, $nextStatus), InvalidOrderStateException::class);

        if ($currentStatus === $nextStatus) {
            return $this->findCompanyOrder($company, $order->id);
        }

        $order->fill(['status' => $nextStatus->value])->save();

        return $this->findCompanyOrder($company, $order->id);
    }

    private function canTransition(OrderStatus $currentStatus, OrderStatus $nextStatus): bool
    {
        if ($currentStatus === $nextStatus) {
            return true;
        }

        return match ($currentStatus) {
            OrderStatus::NEW => in_array($nextStatus, [OrderStatus::IN_PROGRESS, OrderStatus::COMPLETED], true),
            OrderStatus::IN_PROGRESS => $nextStatus === OrderStatus::COMPLETED,
            OrderStatus::COMPLETED => false,
        };
    }
}
