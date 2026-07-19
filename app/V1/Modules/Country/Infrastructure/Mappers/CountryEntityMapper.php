<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Infrastructure\Mappers;

use App\V1\Modules\Country\Domain\Entities\CountryEntity;

class CountryEntityMapper
{
    /**
     * @param array{code: string, name: string, native: string, phone: list<string>, continent: string, capital: string, currency: list<string>, languages: list<string>} $country
     */
    public function parseToEntity(array $country): CountryEntity
    {
        return new CountryEntity(
            code: $country['code'],
            name: $country['name'],
            native: $country['native'],
            phone: $country['phone'],
            continent: $country['continent'],
            capital: $country['capital'],
            currency: $country['currency'],
            languages: $country['languages'],
        );
    }
}
