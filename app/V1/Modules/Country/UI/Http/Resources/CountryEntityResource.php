<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\UI\Http\Resources;

use App\V1\Modules\Country\Domain\Entities\CountryEntity;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;

class CountryEntityResource extends ApiResponseResource
{
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
        /** @var CountryEntity $resource */
        return $this->resource;
    }

    public function getRelationships(): array
    {
        return $this->getRelationshipsNames()->mapWithKeys(fn (string $relationship) => [
            $relationship => match ($relationship) {
                //                'complaint_products' => new CollectionComplaintProductResource(
                //                    $this->getResource()->complaintProducts,
                //                    'names,configurations'
                //                ),
                default => null,
            },
        ])->toArray();
    }
}
