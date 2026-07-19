<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonException;
use JsonSerializable;

/**
 * @implements Arrayable<string, mixed>
 */
readonly class CountryEntity implements Arrayable, JsonSerializable, Jsonable
{
    /**
     * @param list<string> $phone
     * @param list<string> $currency
     * @param list<string> $languages
     */
    public function __construct(
        private string $code,
        private string $name,
        private string $native,
        private array $phone,
        private string $continent,
        private string $capital,
        private array $currency,
        private array $languages,
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

    /**
     * @return list<string>
     */
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

    /**
     * @return list<string>
     */
    public function getCurrency(): array
    {
        return $this->currency;
    }

    /**
     * @return list<string>
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    public function hasLanguage(string $code): bool
    {
        return in_array($code, $this->languages, true);
    }

    /**
     * @return array{code: string, name: string, native: string, phone: list<string>, continent: string, capital: string, currency: list<string>, languages: list<string>}
     */
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

    /**
     * @param array{code: string, name: string, native: string, phone: list<string>, continent: string, capital: string, currency: list<string>, languages: list<string>} $data
     */
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
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @throws JsonException
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | $options);
    }
}
