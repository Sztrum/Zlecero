<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer\UI\Http\Requests;

use App\V1\Modules\Offer\Domain\Enums\OfferDiscountType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiStoreOfferRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'inquiry_id' => ['required', 'string', 'uuid'],
            'number' => ['nullable', 'string', 'max:40'],
            'currency' => ['required', 'string', 'size:3'],
            'issue_date' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after_or_equal:issue_date'],
            'payment_due_days' => ['required', 'integer', 'min:0', 'max:365'],
            'delivery_cost_cents' => ['nullable', 'integer', 'min:0'],
            'discount_type' => [
                'nullable',
                Rule::in([
                    OfferDiscountType::PERCENT->value,
                    OfferDiscountType::AMOUNT->value,
                ]),
            ],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'deposit_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'terms' => ['nullable', 'string', 'max:10000'],
            'notes' => ['nullable', 'string', 'max:10000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string', 'max:5000'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.unit' => ['required', 'string', 'max:20'],
            'items.*.unit_price_cents' => ['required', 'integer', 'min:0'],
            'items.*.tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
