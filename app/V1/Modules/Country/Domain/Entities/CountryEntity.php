<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonException;
use JsonSerializable;

readonly class CountryEntity implements Arrayable, JsonSerializable, Jsonable
{
    public function __construct(
        private string $code,
        private string $name,
        private string $native,
        private array  $phone,
        private string $continent,
        private string $capital,
        private array  $currency,
        private array  $languages,
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNative(): string
    {
        return $this->native;
    }

    public function getPhone(): array
    {
        return $this->phone;
    }

    public function getContinent(): string
    {
        return $this->continent;
    }

    public function getCapital(): string
    {
        return $this->capital;
    }

    public function getCurrency(): array
    {
        return $this->currency;
    }

    public function getLanguages(): array
    {
        return $this->languages;
    }

    public function hasLanguage(string $code): bool
    {
        return in_array($code, $this->languages, true);
    }

    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'native' => $this->getNative(),
            'phone' => $this->getPhone(),
            'continent' => $this->getContinent(),
            'capital' => $this->getCapital(),
            'currency' => $this->getCurrency(),
            'languages' => $this->getLanguages(),
        ];
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'],
            name: $data['name'],
            native: $data['native'],
            phone: $data['phone'],
            continent: $data['continent'],
            capital: $data['capital'],
            currency: $data['currency'],
            languages: $data['languages'],
        );
    }

    /**
     * @throws JsonException
     */
    public function jsonSerialize(): string|bool
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * @param  mixed         $options
     * @throws JsonException
     */
    public function toJson($options = 0): bool|string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | $options);
    }
}
