<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Services;

use App\V1\Modules\Country\Domain\Entities\CountryEntity;
use App\V1\Modules\Country\Domain\Entities\CountryEntityCollection;
use App\V1\Modules\Country\Domain\Exceptions\CountryNativeNameNotFoundException;
use App\V1\Modules\Country\Domain\Exceptions\CountryNotFoundException;
use App\V1\Modules\Country\Infrastructure\Mappers\CountryEntityMapper;
use Illuminate\Config\Repository;
use Throwable;

readonly class CountryService
{
    public function __construct(
        private CountryEntityMapper $countryEntityMapper,
        private Repository          $config,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function getCountryByCode(string $code): CountryEntity
    {
        $config = $this->config->get('countries::countries.countries.' . strtoupper($code));

        throw_if(!$config, CountryNotFoundException::class, 'Country not found');

        return $this->countryEntityMapper->parseToEntity($config + ['code' => $code]);
    }

    /**
     * @throws Throwable
     */
    public function getCountryNativeNameByCode(string $code): string
    {
        $native = $this->getCountryByCode($code)?->getNative();

        throw_if(!$native, CountryNativeNameNotFoundException::class, 'Country native name not found');

        return $native;
    }

    /**
     * Return all countries from config by language code
     */
    public function getCountriesByLanguageCode(string $languageCode): CountryEntityCollection
    {
        $countries = new CountryEntityCollection();

        collect($this->config->get('countries::countries.countries'))->each(function ($country, $code) use (&$countries, $languageCode) {
            $country = $this->countryEntityMapper->parseToEntity($country + [
                'code' => mb_strtolower($code, 'UTF-8'),
            ]);

            if ($country->hasLanguage($languageCode)) {
                $countries->add($country);
            }
        });

        return $countries;
    }

    public function getCurrencyByLangCode(
        string $languageCode,
    ) {
        /** @var CountryEntity $countryEntity */
        $countryEntity = $this->getCountriesByLanguageCode($languageCode)->toCollection()->first();

        if (!$countryEntity) {
            return null;
        }

        return $countryEntity->getCurrency()[0] ?? null;
    }

    public function getAllCountries(): CountryEntityCollection
    {
        $countries = new CountryEntityCollection();

        collect($this->config->get('countries::countries.countries'))->each(function ($country, $code) use (&$countries) {
            $country = $this->countryEntityMapper->parseToEntity($country + [
                'code' => mb_strtolower($code, 'UTF-8'),
            ]);

            $countries->add($country);
        });

        return $countries;
    }
}
