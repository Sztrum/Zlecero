<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\UI\Http\Requests;

use App\V1\Modules\Order\Domain\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiChangeOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in(array_map(static fn (OrderStatus $status): string => $status->value, OrderStatus::cases())),
            ],
        ];
    }
}
