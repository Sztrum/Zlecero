<?php

declare(strict_types=1);

namespace App\V1\Modules\User\UI\Http\Requests;

use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiInviteCompanyUserRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'unique:users'],
            'role' => [
                'required',
                Rule::in([
                    CompanyUserRole::ADMIN->value,
                    CompanyUserRole::MEMBER->value,
                ]),
            ],
        ];
    }
}
