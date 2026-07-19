<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Requests;

use App\V1\Modules\Auth\UI\Http\Rules\IsValidPasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class ApiSetNewPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'confirmed',
                new IsValidPasswordRule(),
            ],
        ];
    }
}
