<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\UI\Http\Resources;

use App\V1\Modules\Customer\Domain\Models\Customer;
use App\V1\Shared\UI\Http\Resources\ApiResponseResource;
use Illuminate\Support\Collection;
use RuntimeException;

class CustomerResource extends ApiResponseResource
{
    /**
     * @param Collection<int, Customer> $potentialDuplicates
     */
    public function __construct(
        $resource,
        protected Collection $potentialDuplicates = new Collection(),
        protected bool $includeHistory = false,
        ?string $relationships = null,
        bool $asResponse = false,
    ) {
        parent::__construct($resource, $relationships, $asResponse);
    }

    /**
     * @return array<string, mixed>
     */
    public function getResourceData(): array
    {
        return [
            'type' => $this->getResource()->type,
            'displayName' => $this->getResource()->display_name,
            'companyName' => $this->getResource()->company_name,
            'firstName' => $this->getResource()->first_name,
            'lastName' => $this->getResource()->last_name,
            'email' => $this->getResource()->email,
            'phone' => $this->getResource()->phone,
            'taxNumber' => $this->getResource()->tax_number,
            'addressLine' => $this->getResource()->address_line,
            'postalCode' => $this->getResource()->postal_code,
            'city' => $this->getResource()->city,
            'countryCode' => $this->getResource()->country_code,
            'notes' => $this->getResource()->notes,
            'potentialDuplicates' => $this->potentialDuplicates
                ->map(static fn (Customer $customer) => [
                    'id' => $customer->id,
                    'displayName' => $customer->display_name,
                    'email' => $customer->email,
                    'companyName' => $customer->company_name,
                    'taxNumber' => $customer->tax_number,
                ])
                ->values()
                ->all(),
            'history' => $this->includeHistory ? [
                'inquiries' => [],
                'messages' => [],
                'offers' => [],
                'orders' => [],
            ] : null,
            'createdAt' => $this->getResource()->created_at?->toIso8601String(),
            'updatedAt' => $this->getResource()->updated_at?->toIso8601String(),
        ];
    }

    public function getResource(): Customer
    {
        if (! $this->resource instanceof Customer) {
            throw new RuntimeException('CustomerResource requires a Customer resource.');
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
