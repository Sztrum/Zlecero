<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer\UI\Http\Resources;

use App\V1\Modules\Offer\Domain\Models\OfferItem;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class OfferItemResource extends ApiResponseResource
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

    public function getResource(): OfferItem
    {
        if (! $this->resource instanceof OfferItem) {
            throw new RuntimeException('OfferItemResource requires an OfferItem resource.');
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
