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
            'role' => $this->getResource()->role,
            'status' => $this->getResource()->status,
            'company' => $this->getResource()->company ? [
                'id' => $this->getResource()->company->id,
                'name' => $this->getResource()->company->name,
                'slug' => $this->getResource()->company->slug,
                'trialEndsAt' => $this->getResource()->company->trial_ends_at?->toIso8601String(),
                'onboardingCompletedAt' => $this->getResource()->company->onboarding_completed_at?->toIso8601String(),
            ] : null,
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
