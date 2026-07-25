<?php

declare(strict_types=1);

namespace App\V1\Modules\Company\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiUpdateCompanyRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'billing_name' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:64'],
            'contact_email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:64'],
            'address_line' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:32'],
            'city' => ['nullable', 'string', 'max:128'],
            'country_code' => ['required', 'string', 'size:2'],
            'brand_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ];
    }
}
