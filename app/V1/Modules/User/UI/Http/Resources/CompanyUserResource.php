<?php

declare(strict_types=1);

namespace App\V1\Modules\User\UI\Http\Resources;

use App\V1\Modules\User\Domain\Models\User;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class CompanyUserResource extends ApiResponseResource
{
    /**
     * @return array<string, mixed>
     */
    public function getResourceData(): array
    {
        return [
            'id' => $this->getResource()->id,
            'name' => $this->getResource()->name,
            'email' => $this->getResource()->email,
            'role' => $this->getResource()->role,
            'status' => $this->getResource()->status,
            'invitedAt' => $this->getResource()->invited_at?->toIso8601String(),
            'deactivatedAt' => $this->getResource()->deactivated_at?->toIso8601String(),
        ];
    }

    public function getResource(): User
    {
        if (! $this->resource instanceof User) {
            throw new RuntimeException('CompanyUserResource requires a User resource.');
        }

        return $this->resource;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRelationships(): array
    {
        return [];
    }
}
