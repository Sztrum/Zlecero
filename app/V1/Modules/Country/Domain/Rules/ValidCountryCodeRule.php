<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Rules;

use App\V1\Modules\Country\Domain\Services\CountryService;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Throwable;

class ValidCountryCodeRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        /** @var CountryService $countryService */
        $countryService = resolve(CountryService::class);

        try {
            $countryService->getCountryByCode($value);

            return true;
        } catch (Exception|Throwable $e) {
            return false;
        }
    }

    public function message(): string
    {
        return __('countries::validation.invalid_country_code');
    }
}
