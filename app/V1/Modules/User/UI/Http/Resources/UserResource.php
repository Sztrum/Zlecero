<?php

declare(strict_types=1);

namespace App\V1\Modules\User\UI\Http\Resources;

use App\V1\Modules\User\Domain\Models\User;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class UserResource extends ApiResponseResource
{
    /**
     * @return array<string, mixed>
     */
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
        if (!$this->resource instanceof User) {
            throw new RuntimeException('UserResource requires a User resource.');
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
