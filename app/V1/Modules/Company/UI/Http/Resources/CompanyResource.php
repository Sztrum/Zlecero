<?php

declare(strict_types=1);

namespace App\V1\Modules\Company\UI\Http\Resources;

use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use RuntimeException;

class CompanyResource extends ApiResponseResource
{
    /**
     * @return array<string, mixed>
     */
    public function getResourceData(): array
    {
        return [
            'name' => $this->getResource()->name,
            'slug' => $this->getResource()->slug,
            'billingName' => $this->getResource()->billing_name,
            'taxNumber' => $this->getResource()->tax_number,
            'contactEmail' => $this->getResource()->contact_email,
            'contactPhone' => $this->getResource()->contact_phone,
            'addressLine' => $this->getResource()->address_line,
            'postalCode' => $this->getResource()->postal_code,
            'city' => $this->getResource()->city,
            'countryCode' => $this->getResource()->country_code,
            'brandColor' => $this->getResource()->brand_color,
            'trialDays' => $this->getResource()->trial_days,
            'trialStartedAt' => $this->getResource()->trial_started_at?->toIso8601String(),
            'trialEndsAt' => $this->getResource()->trial_ends_at?->toIso8601String(),
            'onboardingCompletedAt' => $this->getResource()->onboarding_completed_at?->toIso8601String(),
        ];
    }

    public function getResource(): Company
    {
        if (! $this->resource instanceof Company) {
            throw new RuntimeException('CompanyResource requires a Company resource.');
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
