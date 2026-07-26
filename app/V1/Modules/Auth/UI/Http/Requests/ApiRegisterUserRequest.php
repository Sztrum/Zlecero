<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ApiRegisterUserRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge([
                'email' => strtolower(trim($this->input('email'))),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'email' => ['string', 'required', 'email:rfc', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
            'terms_accepted' => ['accepted'],
        ];
    }
}
