<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiAssignInquiryOwnerRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'owner_user_id' => ['nullable', 'string', 'uuid'],
        ];
    }
}
