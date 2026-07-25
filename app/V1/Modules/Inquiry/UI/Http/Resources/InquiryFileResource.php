<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\UI\Http\Resources;

use App\V1\Modules\Inquiry\Domain\Models\InquiryFile;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class InquiryFileResource extends ApiResponseResource
{
    /**
     * @return array<string, mixed>
     */
    public function getResourceData(): array
    {
        return [
            'source' => $this->getResource()->source,
            'originalName' => $this->getResource()->original_name,
            'mimeType' => $this->getResource()->mime_type,
            'sizeBytes' => $this->getResource()->size_bytes,
            'category' => $this->getResource()->category,
            'description' => $this->getResource()->description,
            'uploadedByUserId' => $this->getResource()->uploaded_by_user_id,
            'messageId' => $this->getResource()->inquiry_message_id,
            'downloadUrl' => sprintf(
                '/api/v1/inquiries/%s/files/%s/download',
                $this->getResource()->inquiry_id,
                $this->getResource()->id,
            ),
            'createdAt' => $this->getResource()->created_at?->toIso8601String(),
            'updatedAt' => $this->getResource()->updated_at?->toIso8601String(),
        ];
    }

    public function getResource(): InquiryFile
    {
        if (! $this->resource instanceof InquiryFile) {
            throw new RuntimeException('InquiryFileResource requires an InquiryFile resource.');
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
