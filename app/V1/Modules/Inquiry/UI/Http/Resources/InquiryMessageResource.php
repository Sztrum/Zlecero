<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\UI\Http\Resources;

use App\V1\Modules\Inquiry\Domain\Models\InquiryMessage;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class InquiryMessageResource extends ApiResponseResource
{
    /**
     * @return array<string, mixed>
     */
    public function getResourceData(): array
    {
        return [
            'direction' => $this->getResource()->direction,
            'senderName' => $this->getResource()->sender_name,
            'senderEmail' => $this->getResource()->sender_email,
            'recipientEmail' => $this->getResource()->recipient_email,
            'subject' => $this->getResource()->subject,
            'body' => $this->getResource()->body,
            'externalMessageId' => $this->getResource()->external_message_id,
            'externalThreadId' => $this->getResource()->external_thread_id,
            'sentAt' => $this->getResource()->sent_at?->toIso8601String(),
            'createdAt' => $this->getResource()->created_at?->toIso8601String(),
        ];
    }

    public function getResource(): InquiryMessage
    {
        if (! $this->resource instanceof InquiryMessage) {
            throw new RuntimeException('InquiryMessageResource requires an InquiryMessage resource.');
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
