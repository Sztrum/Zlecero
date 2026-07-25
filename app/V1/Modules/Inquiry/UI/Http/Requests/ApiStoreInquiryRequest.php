<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\UI\Http\Requests;

use App\V1\Modules\Inquiry\Domain\Enums\InquiryPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiStoreInquiryRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['nullable', 'string', 'uuid'],
            'owner_user_id' => ['nullable', 'string', 'uuid'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'priority' => [
                'required',
                Rule::in([
                    InquiryPriority::LOW->value,
                    InquiryPriority::NORMAL->value,
                    InquiryPriority::HIGH->value,
                    InquiryPriority::URGENT->value,
                ]),
            ],
            'response_due_at' => ['nullable', 'date'],
            'realization_due_at' => ['nullable', 'date', 'after_or_equal:response_due_at'],
            'pickup_due_at' => ['nullable', 'date', 'after_or_equal:realization_due_at'],
        ];
    }
}
