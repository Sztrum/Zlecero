<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\Application\Commands;

use App\V1\Core\Application\Command\CommandTransactionalInterface;
use App\V1\Modules\Customer\Domain\Models\Customer;

readonly class UpdateCustomerCommand implements CommandTransactionalInterface
{
    public function __construct(
        public Customer $customer,
        public string $type,
        public string $displayName,
        public ?string $companyName,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $email,
        public ?string $phone,
        public ?string $taxNumber,
        public ?string $addressLine,
        public ?string $postalCode,
        public ?string $city,
        public string $countryCode,
        public ?string $notes,
    ) {
    }
}
