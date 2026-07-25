<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\UI\Http\Requests;

use App\V1\Modules\Inquiry\Domain\Enums\InquiryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiChangeInquiryStatusRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    InquiryStatus::NEW->value,
                    InquiryStatus::TRIAGE->value,
                    InquiryStatus::WAITING_FOR_CUSTOMER->value,
                    InquiryStatus::PREPARING_OFFER->value,
                    InquiryStatus::OFFER_SENT->value,
                    InquiryStatus::ACCEPTED->value,
                    InquiryStatus::REJECTED->value,
                    InquiryStatus::CLOSED->value,
                ]),
            ],
        ];
    }
}
