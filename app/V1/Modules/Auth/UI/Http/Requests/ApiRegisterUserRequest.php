<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ApiRegisterUserRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'email' => ['string', 'required', 'email:rfc,dns', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
            'terms_accepted' => ['accepted'],
        ];
    }
}
