<?php

declare(strict_types=1);

namespace App\V1\Modules\User\UI\Http\Resources;

use App\V1\Modules\User\Domain\Models\User;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;

class UserResource extends ApiResponseResource
{
    public function getResourceData(): array
    {
        return [
            'name' => $this->getResource()->name,
            'email' => $this->getResource()->email,
            'avatar' => 'https://picsum.photos/150/150',
        ];
    }

    public function getResource(): User
    {
        /** @var User $resource */
        return $this->resource;
    }

    public function getRelationships(): array
    {
        return $this->getRelationshipsNames()->mapWithKeys(fn (string $relationship) => [
            $relationship => match ($relationship) {
                default => null,
            },
        ])->toArray();
    }
}
