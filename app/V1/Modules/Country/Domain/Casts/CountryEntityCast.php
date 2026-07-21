<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Casts;

use App\V1\Modules\Country\Domain\Entities\CountryEntity;
use App\V1\Modules\Country\Domain\Services\CountryService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Throwable;

/**
 * @implements CastsAttributes<CountryEntity|null, CountryEntity|string|null>
 */
readonly class CountryEntityCast implements CastsAttributes
{
    /**
     * @param array<string, mixed> $attributes
     * @throws Throwable
     */
    public function get($model, string $key, $value, array $attributes): ?CountryEntity
    {
        if (!is_string($value) || $value === '') {
            return null;
        }

        return resolve(CountryService::class)->getCountryByCode($value);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function set($model, string $key, $value, array $attributes): ?string
    {
        return $value instanceof CountryEntity ? $value->getCode() : (is_string($value) ? $value : null);
    }
}
