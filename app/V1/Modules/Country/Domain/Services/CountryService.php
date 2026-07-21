<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Services;

use App\V1\Modules\Country\Domain\Entities\CountryEntity;
use App\V1\Modules\Country\Domain\Entities\CountryEntityCollection;
use App\V1\Modules\Country\Domain\Exceptions\CountryNativeNameNotFoundException;
use App\V1\Modules\Country\Domain\Exceptions\CountryNotFoundException;
use App\V1\Modules\Country\Infrastructure\Mappers\CountryEntityMapper;
use Illuminate\Config\Repository;
use RuntimeException;
use Throwable;

readonly class CountryService
{
    public function __construct(
        private CountryEntityMapper $countryEntityMapper,
        private Repository $config,
    ) {}

    /**
     * @throws Throwable
     */
    public function getCountryByCode(string $code): CountryEntity
    {
        $countryConfig = $this->config->get('countries::countries.countries.'.strtoupper($code));

        if (! is_array($countryConfig)) {
            throw new CountryNotFoundException;
        }

        return $this->countryEntityMapper->parseToEntity($this->countryData($countryConfig, $code));
    }

    /**
     * @throws Throwable
     */
    public function getCountryNativeNameByCode(string $code): string
    {
        $native = $this->getCountryByCode($code)->getNative();

        throw_if(! $native, CountryNativeNameNotFoundException::class);

        return $native;
    }

    public function getCountriesByLanguageCode(string $languageCode): CountryEntityCollection
    {
        $countries = new CountryEntityCollection;

        foreach ($this->countriesConfig() as $code => $countryConfig) {
            $country = $this->countryEntityMapper->parseToEntity($this->countryData($countryConfig, $code));

            if ($country->hasLanguage($languageCode)) {
                $countries->add($country);
            }
        }

        return $countries;
    }

    public function getCurrencyByLangCode(string $languageCode): ?string
    {
        $countryEntity = $this->getCountriesByLanguageCode($languageCode)->toCollection()->first();

        if (! $countryEntity instanceof CountryEntity) {
            return null;
        }

        return $countryEntity->getCurrency()[0] ?? null;
    }

    public function getAllCountries(): CountryEntityCollection
    {
        $countries = new CountryEntityCollection;

        foreach ($this->countriesConfig() as $code => $countryConfig) {
            $countries->add($this->countryEntityMapper->parseToEntity($this->countryData($countryConfig, $code)));
        }

        return $countries;
    }

    /**
     * @return array<string, array<array-key, mixed>>
     */
    private function countriesConfig(): array
    {
        $countries = $this->config->get('countries::countries.countries');

        if (! is_array($countries)) {
            throw new RuntimeException('Config countries::countries.countries must be an array.');
        }

        $typedCountries = [];

        foreach ($countries as $code => $country) {
            if (! is_string($code) || ! is_array($country)) {
                throw new RuntimeException('Each country config entry must be keyed by country code and contain an array.');
            }

            $typedCountries[$code] = $country;
        }

        return $typedCountries;
    }

    /**
     * @param  array<array-key, mixed>  $country
     * @return array{code: string, name: string, native: string, phone: list<string>, continent: string, capital: string, currency: list<string>, languages: list<string>}
     */
    private function countryData(array $country, string $code): array
    {
        foreach (['name', 'native', 'continent', 'capital'] as $key) {
            if (! isset($country[$key]) || ! is_string($country[$key])) {
                throw new RuntimeException("Country config key [{$key}] must be a string.");
            }
        }

        return [
            'code' => mb_strtolower($code, 'UTF-8'),
            'name' => $country['name'],
            'native' => $country['native'],
            'phone' => $this->stringList($country['phone'] ?? []),
            'continent' => $country['continent'],
            'capital' => $country['capital'],
            'currency' => $this->stringList($country['currency'] ?? []),
            'languages' => $this->stringList($country['languages'] ?? []),
        ];
    }

    /**
     * @return list<string>
     */
    private function stringList(mixed $value): array
    {
        if (! is_array($value)) {
            throw new RuntimeException('Country config list value must be an array.');
        }

        $items = [];

        foreach ($value as $item) {
            if (! is_string($item)) {
                throw new RuntimeException('Country config list item must be a string.');
            }

            $items[] = $item;
        }

        return $items;
    }
}
