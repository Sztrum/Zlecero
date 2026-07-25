<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\Application\Commands;

use App\V1\Core\Application\Command\CommandTransactionalInterface;

readonly class CreateCustomerCommand implements CommandTransactionalInterface
{
    public function __construct(
        public string $id,
        public string $companyId,
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
