<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\UI\Http\Requests;

use App\V1\Modules\Inquiry\Domain\Enums\InquiryMessageDirection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiStoreInquiryMessageRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'direction' => [
                'required',
                Rule::in([
                    InquiryMessageDirection::INBOUND->value,
                    InquiryMessageDirection::OUTBOUND->value,
                    InquiryMessageDirection::INTERNAL->value,
                ]),
            ],
            'sender_name' => ['nullable', 'string', 'max:255'],
            'sender_email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'recipient_email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:50000'],
            'external_message_id' => ['nullable', 'string', 'max:255'],
            'external_thread_id' => ['nullable', 'string', 'max:255'],
            'sent_at' => ['nullable', 'date'],
        ];
    }
}
