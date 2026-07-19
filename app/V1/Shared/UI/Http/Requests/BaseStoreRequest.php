<?php

declare(strict_types=1);

namespace App\V1\Shared\UI\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

abstract class BaseStoreRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'fields.*.name' => ['nullable', 'string'],
        ] + $this->getContentSectionsRules();
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $fields = $this->input('fields', []);
            $name = $this->input('name', []);

            $nameFilled = false;
            $errorKey = '';

            foreach ($fields as $lang => $fieldData) {
                if (!empty($fieldData['name'])) {
                    $nameFilled = true;

                    break;
                }

                if ($errorKey === '') {
                    $errorKey = 'fields.' . $lang . '.name';
                }
            }

            if (!$nameFilled) {
                foreach ($name as $lang => $nameValue) {
                    if (!empty($nameValue)) {
                        $nameFilled = true;

                        break;
                    }

                    if ($errorKey === '') {
                        $errorKey = 'name.' . $lang;
                    }
                }
            }

            if (!$nameFilled) {
                $validator->errors()->add($errorKey, 'Pole "name" musi być wypełnione w co najmniej jednym języku.');
            }
        });
    }


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

    private function saveContentSectionsToSession()
    {
        session()->flash('failedValidationContentSections', $this->get('content_sections'));
    }

    protected function failedValidation(Validator $validator)
    {
        /** @var ValidationException $exception */
        $exception = $validator->getException();

        if ($this->get('content_sections')) {
            $this->saveContentSectionsToSession();
        }

        throw (new $exception($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
