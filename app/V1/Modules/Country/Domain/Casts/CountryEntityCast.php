<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Casts;

use App\V1\Modules\Country\Domain\Entities\CountryEntity;
use App\V1\Modules\Country\Domain\Services\CountryService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Throwable;

readonly class CountryEntityCast implements CastsAttributes
{
    /**
     * @param  mixed     $model
     * @param  mixed     $value
     * @throws Throwable
     */
    public function get($model, string $key, $value, array $attributes): ?CountryEntity
    {
        /** @var CountryService $countryService */
        $countryService = resolve(CountryService::class);

        if (!$value) {
            return null;
        }

        return $countryService->getCountryByCode($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value instanceof CountryEntity ? $value->getCode() : $value;
    }
}
