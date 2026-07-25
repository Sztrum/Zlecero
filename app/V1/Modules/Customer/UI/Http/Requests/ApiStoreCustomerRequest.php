<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\UI\Http\Requests;

use App\V1\Modules\Customer\Domain\Enums\CustomerType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiStoreCustomerRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in([CustomerType::COMPANY->value, CustomerType::INDIVIDUAL->value])],
            'display_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:128'],
            'last_name' => ['nullable', 'string', 'max:128'],
            'email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'tax_number' => ['nullable', 'string', 'max:64'],
            'address_line' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:32'],
            'city' => ['nullable', 'string', 'max:128'],
            'country_code' => ['required', 'string', 'size:2'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
