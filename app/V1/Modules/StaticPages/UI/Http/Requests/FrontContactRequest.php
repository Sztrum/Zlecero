<?php

declare(strict_types=1);

namespace App\V1\Modules\StaticPages\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'company' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
            'website' => ['nullable', 'prohibited'],
        ];
    }
}
