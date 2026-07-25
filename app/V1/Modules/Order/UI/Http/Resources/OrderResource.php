<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\UI\Http\Resources;

use App\V1\Modules\Order\Domain\Models\Order;
use App\V1\Modules\Order\Domain\Models\OrderItem;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class OrderResource extends ApiResponseResource
{
    /**
     * @return array<string, mixed>
     */
    public function getResourceData(): array
    {
        return [
            'inquiryId' => $this->getResource()->inquiry_id,
            'offerId' => $this->getResource()->offer_id,
            'customer' => $this->getResource()->customer ? [
                'id' => $this->getResource()->customer->id,
                'displayName' => $this->getResource()->customer->display_name,
                'email' => $this->getResource()->customer->email,
            ] : null,
            'owner' => $this->getResource()->owner ? [
                'id' => $this->getResource()->owner->id,
                'name' => $this->getResource()->owner->name,
                'email' => $this->getResource()->owner->email,
            ] : null,
            'number' => $this->getResource()->number,
            'status' => $this->getResource()->status,
            'currency' => $this->getResource()->currency,
            'acceptedDate' => $this->getResource()->accepted_date->toDateString(),
            'paymentDueDate' => $this->getResource()->payment_due_date?->toDateString(),
            'realizationDueDate' => $this->getResource()->realization_due_date?->toDateString(),
            'pickupDueDate' => $this->getResource()->pickup_due_date?->toDateString(),
            'terms' => $this->getResource()->terms,
            'notes' => $this->getResource()->notes,
            'subtotalNetCents' => $this->getResource()->subtotal_net_cents,
            'discountCents' => $this->getResource()->discount_cents,
            'taxCents' => $this->getResource()->tax_cents,
            'totalGrossCents' => $this->getResource()->total_gross_cents,
            'depositCents' => $this->getResource()->deposit_cents,
            'items' => $this->getResource()->items
                ->map(static fn (OrderItem $item) => (new OrderItemResource($item))->toArray(request()))
                ->values()
                ->all(),
            'createdAt' => $this->getResource()->created_at?->toIso8601String(),
            'updatedAt' => $this->getResource()->updated_at?->toIso8601String(),
        ];
    }

    public function getResource(): Order
    {
        if (! $this->resource instanceof Order) {
            throw new RuntimeException('OrderResource requires an Order resource.');
        }

        return $this->resource;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRelationships(): array
    {
        return [];
    }
}
