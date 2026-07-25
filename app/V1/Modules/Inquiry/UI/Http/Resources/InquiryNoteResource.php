<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\UI\Http\Resources;

use App\V1\Modules\Inquiry\Domain\Models\InquiryNote;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class InquiryNoteResource extends ApiResponseResource
{
    /**
     * @return array<string, mixed>
     */
    public function getResourceData(): array
    {
        return [
            'body' => $this->getResource()->body,
            'isInternal' => $this->getResource()->is_internal,
            'author' => $this->getResource()->author ? [
                'id' => $this->getResource()->author->id,
                'name' => $this->getResource()->author->name,
                'email' => $this->getResource()->author->email,
            ] : null,
            'createdAt' => $this->getResource()->created_at?->toIso8601String(),
            'updatedAt' => $this->getResource()->updated_at?->toIso8601String(),
        ];
    }

    public function getResource(): InquiryNote
    {
        if (! $this->resource instanceof InquiryNote) {
            throw new RuntimeException('InquiryNoteResource requires an InquiryNote resource.');
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
