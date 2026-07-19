<?php

declare(strict_types=1);

namespace App\V1\Shared\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseIndexRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:0', 'max:100'],
            'search' => ['nullable', 'string'],
        ];
    }
}
