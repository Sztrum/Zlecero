<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\Customer\Application\Commands\UpdateCustomerCommand;
use App\V1\Modules\Customer\Infrastructure\Repositories\CustomerRepository;
use RuntimeException;

readonly class UpdateCustomerCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CustomerRepository $customerRepository,
    ) {
    }

    public function handle(CommandInterface $command): void
    {
        if (! $command instanceof UpdateCustomerCommand) {
            throw new RuntimeException('UpdateCustomerCommandHandler expects UpdateCustomerCommand.');
        }

        $this->customerRepository->update($command->customer, [
            'type' => $command->type,
            'display_name' => $command->displayName,
            'company_name' => $command->companyName,
            'first_name' => $command->firstName,
            'last_name' => $command->lastName,
            'email' => $command->email,
            'phone' => $command->phone,
            'tax_number' => $command->taxNumber,
            'address_line' => $command->addressLine,
            'postal_code' => $command->postalCode,
            'city' => $command->city,
            'country_code' => $command->countryCode,
            'notes' => $command->notes,
        ]);
    }
}
