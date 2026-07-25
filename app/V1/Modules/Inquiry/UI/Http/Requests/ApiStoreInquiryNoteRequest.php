<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiStoreInquiryNoteRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:10000'],
        ];
    }
}
