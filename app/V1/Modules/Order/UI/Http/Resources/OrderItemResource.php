<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\UI\Http\Resources;

use App\V1\Modules\Order\Domain\Models\OrderItem;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class OrderItemResource extends ApiResponseResource
{
    /**
     * @return array<string, mixed>
     */
    public function getResourceData(): array
    {
        return [
            'position' => $this->getResource()->position,
            'name' => $this->getResource()->name,
            'description' => $this->getResource()->description,
            'quantity' => $this->getResource()->quantity,
            'unit' => $this->getResource()->unit,
            'unitPriceCents' => $this->getResource()->unit_price_cents,
            'taxRate' => $this->getResource()->tax_rate,
            'netCents' => $this->getResource()->net_cents,
            'taxCents' => $this->getResource()->tax_cents,
            'grossCents' => $this->getResource()->gross_cents,
        ];
    }

    public function getResource(): OrderItem
    {
        if (! $this->resource instanceof OrderItem) {
            throw new RuntimeException('OrderItemResource requires an OrderItem resource.');
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
