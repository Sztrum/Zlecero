<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\Order\Domain\Enums\OrderStatus;
use App\V1\Modules\Order\Infrastructure\Repositories\OrderRepository;
use App\V1\Modules\Order\UI\Http\Requests\ApiChangeOrderStatusRequest;
use App\V1\Modules\Order\UI\Http\Resources\OrderResource;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Throwable;

class ApiOrderController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
        private readonly UserRepository $userRepository,
        private readonly OrderRepository $orderRepository,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    /**
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderRepository
            ->getByCompany($this->userRepository->getAuthenticatedUserCompany())
            ->map(static fn ($order) => (new OrderResource($order))->toArray($request))
            ->values()
            ->all();

        return $this->responseData(['orders' => $orders]);
    }

    /**
     * @throws Throwable
     */
    public function show(Request $request): OrderResource
    {
        return new OrderResource(
            resource: $this->orderRepository->findCompanyOrder(
                $this->userRepository->getAuthenticatedUserCompany(),
                $this->routeString($request, 'order_id'),
            ),
            asResponse: true,
        );
    }

    /**
     * @throws Throwable
     */
    public function changeStatus(ApiChangeOrderStatusRequest $request): OrderResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $order = $this->orderRepository->findCompanyOrder($company, $this->routeString($request, 'order_id'));

        return new OrderResource(
            resource: $this->orderRepository->changeStatus(
                $company,
                $order,
                OrderStatus::from($this->validatedString($request, 'status')),
            ),
            asResponse: true,
        );
    }

    private function routeString(Request $request, string $key): string
    {
        $value = $request->route($key);

        if (! is_string($value)) {
            throw new RuntimeException("Route parameter [{$key}] must be a string.");
        }

        return $value;
    }

    private function validatedString(ApiChangeOrderStatusRequest $request, string $key): string
    {
        $value = $request->validated($key);

        if (! is_string($value)) {
            throw new RuntimeException("Validated field [{$key}] must be a string.");
        }

        return $value;
    }
}
