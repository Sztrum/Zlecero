<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Requests;

use App\V1\Modules\Auth\UI\Http\Rules\IsValidPasswordRule;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Http\FormRequest;

class ApiResetPasswordRequest extends FormRequest
{
    public function authorize(AuthManager $authManager): bool
    {
        return $authManager->guard()->user() == null;
    }

    public function rules(): array
    {
        return [
            'token' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                new IsValidPasswordRule(),
            ],
        ];
    }
}
