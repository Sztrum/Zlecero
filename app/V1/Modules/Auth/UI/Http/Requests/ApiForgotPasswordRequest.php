<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Requests;

use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Http\FormRequest;

class ApiForgotPasswordRequest extends FormRequest
{
    public function authorize(AuthManager $authManager): bool
    {
        return $authManager->guard()->user() === null;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'string',
                'required',
                'email:rfc,dns',
            ],
        ];
    }
}
