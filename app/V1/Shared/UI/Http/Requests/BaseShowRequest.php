<?php

declare(strict_types=1);

namespace App\V1\Shared\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseShowRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'relationships' => ['string', 'nullable'],
        ];
    }
}
