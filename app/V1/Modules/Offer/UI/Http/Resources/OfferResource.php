<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer\UI\Http\Resources;

use App\V1\Modules\Offer\Domain\Models\Offer;
use App\V1\Modules\Offer\Domain\Models\OfferItem;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class OfferResource extends ApiResponseResource
{
    /**
     * @return array<string, mixed>
     */
    public function getResourceData(): array
    {
        return [
            'inquiryId' => $this->getResource()->inquiry_id,
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
            'issueDate' => $this->getResource()->issue_date->toDateString(),
            'validUntil' => $this->getResource()->valid_until->toDateString(),
            'paymentDueDays' => $this->getResource()->payment_due_days,
            'deliveryCostCents' => $this->getResource()->delivery_cost_cents,
            'discountType' => $this->getResource()->discount_type,
            'discountValue' => $this->getResource()->discount_value,
            'depositPercent' => $this->getResource()->deposit_percent,
            'terms' => $this->getResource()->terms,
            'notes' => $this->getResource()->notes,
            'subtotalNetCents' => $this->getResource()->subtotal_net_cents,
            'discountCents' => $this->getResource()->discount_cents,
            'taxCents' => $this->getResource()->tax_cents,
            'totalGrossCents' => $this->getResource()->total_gross_cents,
            'depositCents' => $this->getResource()->deposit_cents,
            'pdf' => $this->getResource()->pdf_path ? [
                'generatedAt' => $this->getResource()->pdf_generated_at?->toIso8601String(),
                'downloadUrl' => sprintf('/api/v1/offers/%s/pdf/download', $this->getResource()->id),
            ] : null,
            'orderId' => $this->getResource()->order?->id,
            'sentAt' => $this->getResource()->sent_at?->toIso8601String(),
            'acceptedAt' => $this->getResource()->accepted_at?->toIso8601String(),
            'rejectedAt' => $this->getResource()->rejected_at?->toIso8601String(),
            'items' => $this->getResource()->items
                ->map(static fn (OfferItem $item) => (new OfferItemResource($item))->toArray(request()))
                ->values()
                ->all(),
            'createdAt' => $this->getResource()->created_at?->toIso8601String(),
            'updatedAt' => $this->getResource()->updated_at?->toIso8601String(),
        ];
    }

    public function getResource(): Offer
    {
        if (! $this->resource instanceof Offer) {
            throw new RuntimeException('OfferResource requires an Offer resource.');
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
