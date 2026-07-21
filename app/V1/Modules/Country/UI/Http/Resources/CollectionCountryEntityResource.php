<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\UI\Http\Resources;

use App\V1\Shared\UI\Http\Resources\ApiCollectionResource;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;

class CollectionCountryEntityResource extends ApiCollectionResource
{
    public function getResource(mixed $model): ApiResponseResource
    {
        return new CountryEntityResource(
            $model,
            $this->relationships,
        );
    }
}
