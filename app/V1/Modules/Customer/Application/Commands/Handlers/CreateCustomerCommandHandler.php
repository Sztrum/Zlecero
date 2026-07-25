<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\Customer\Application\Commands\CreateCustomerCommand;
use App\V1\Modules\Customer\Infrastructure\Repositories\CustomerRepository;
use RuntimeException;

readonly class CreateCustomerCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CustomerRepository $customerRepository,
    ) {
    }

    public function handle(CommandInterface $command): void
    {
        if (! $command instanceof CreateCustomerCommand) {
            throw new RuntimeException('CreateCustomerCommandHandler expects CreateCustomerCommand.');
        }

        $this->customerRepository->create([
            'id' => $command->id,
            'company_id' => $command->companyId,
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
