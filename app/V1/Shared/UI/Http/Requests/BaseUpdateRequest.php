<?php

declare(strict_types=1);

namespace App\V1\Shared\UI\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator as LaravelValidator;

abstract class BaseUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'fields.*.name' => ['nullable', 'string'],
        ] + $this->getContentSectionsRules();
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (LaravelValidator $validator): void {
            $fields = $this->arrayInput('fields');
            $nameFilled = false;
            $errorKey = '';

            foreach ($fields as $lang => $fieldData) {
                if (is_array($fieldData) && !empty($fieldData['name'])) {
                    $nameFilled = true;

                    break;
                }

                if ($errorKey === '') {
                    $errorKey = 'fields.' . $lang . '.name';
                }
            }

            if (!$nameFilled) {
                $validator->errors()->add($errorKey, 'Pole "name" musi być wypełnione w co najmniej jednym języku.');
            }
        });
    }

    /**
     * @return array<string, mixed>
     */
    protected function getContentSectionsRules(): array
    {
        return [
            'content_sections' => ['array'],
            'content_sections.*.name' => ['required', 'string'],
            'content_sections.*.type' => ['required', 'string'],
            'content_sections.*.items' => ['required', 'array'],
            'content_sections.*.items.*.name' => ['required', 'string'],
        ];
    }

    private function saveContentSectionsToSession(): void
    {
        session()->flash('failedValidationContentSections', $this->get('content_sections'));
    }

    /**
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->get('content_sections')) {
            $this->saveContentSectionsToSession();
        }

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * @return array<array-key, mixed>
     */
    private function arrayInput(string $key): array
    {
        $value = $this->input($key, []);

        return is_array($value) ? $value : [];
    }
}
