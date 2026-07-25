<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Company\Domain\Models\Company;
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
}
