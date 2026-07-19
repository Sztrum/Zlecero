<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\UI\Http\Resources;

use App\V1\Modules\Country\Domain\Entities\CountryEntity;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class CountryEntityResource extends ApiResponseResource
{
    /**
     * @return array<string, mixed>
     */
    public function getResourceData(): array
    {
        return [
            'code' => $this->getResource()->getCode(),
            'name' => $this->getResource()->getName(),
            'native' => $this->getResource()->getNative(),
            'phone' => $this->getResource()->getPhone(),
            'continent' => $this->getResource()->getContinent(),
            'capital' => $this->getResource()->getCapital(),
            'currency' => $this->getResource()->getCurrency(),
            'languages' => $this->getResource()->getLanguages(),
        ];
    }

    public function getResource(): CountryEntity
    {
        if (!$this->resource instanceof CountryEntity) {
            throw new RuntimeException('CountryEntityResource requires a CountryEntity resource.');
        }

        return $this->resource;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRelationships(): array
    {
        $relationships = [];

        foreach ($this->getRelationshipsNames() as $relationship) {
            $relationships[$relationship] = match ($relationship) {
                default => null,
            };
        }

        return $relationships;
    }
}
