<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiStoreInquiryFileRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:20480',
                'mimes:csv,doc,docx,dwg,dxf,jpeg,jpg,pdf,png,txt,webp,xls,xlsx',
            ],
            'category' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
