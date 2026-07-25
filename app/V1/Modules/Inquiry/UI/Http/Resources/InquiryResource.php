<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\UI\Http\Resources;

use App\V1\Modules\Inquiry\Domain\Models\Inquiry;
use App\V1\Modules\Inquiry\Domain\Models\InquiryMessage;
use App\V1\Modules\Inquiry\Domain\Models\InquiryStatusChange;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class InquiryResource extends ApiResponseResource
{
    /**
     * @return array<string, mixed>
     */
    public function getResourceData(): array
    {
        return [
            'title' => $this->getResource()->title,
            'description' => $this->getResource()->description,
            'source' => $this->getResource()->source,
            'status' => $this->getResource()->status,
            'priority' => $this->getResource()->priority,
            'responseDueAt' => $this->getResource()->response_due_at?->toIso8601String(),
            'realizationDueAt' => $this->getResource()->realization_due_at?->toIso8601String(),
            'pickupDueAt' => $this->getResource()->pickup_due_at?->toIso8601String(),
            'archivedAt' => $this->getResource()->archived_at?->toIso8601String(),
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
            'messages' => $this->getResource()->messages
                ->map(static fn (InquiryMessage $message) => (new InquiryMessageResource($message))->toArray(request()))
                ->values()
                ->all(),
            'statusChanges' => $this->getResource()->statusChanges
                ->map(static fn (InquiryStatusChange $statusChange) => [
                    'id' => $statusChange->id,
                    'fromStatus' => $statusChange->from_status,
                    'toStatus' => $statusChange->to_status,
                    'changedByUserId' => $statusChange->changed_by_user_id,
                    'changedAt' => $statusChange->changed_at->toIso8601String(),
                ])
                ->values()
                ->all(),
            'createdAt' => $this->getResource()->created_at?->toIso8601String(),
            'updatedAt' => $this->getResource()->updated_at?->toIso8601String(),
        ];
    }

    public function getResource(): Inquiry
    {
        if (! $this->resource instanceof Inquiry) {
            throw new RuntimeException('InquiryResource requires an Inquiry resource.');
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
