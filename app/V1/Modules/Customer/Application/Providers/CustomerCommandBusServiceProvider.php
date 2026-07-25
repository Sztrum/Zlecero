<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\Application\Providers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\Application\Providers\ServiceProvider;
use App\V1\Modules\Customer\Application\Commands\CreateCustomerCommand;
use App\V1\Modules\Customer\Application\Commands\Handlers\CreateCustomerCommandHandler;
use App\V1\Modules\Customer\Application\Commands\Handlers\UpdateCustomerCommandHandler;
use App\V1\Modules\Customer\Application\Commands\UpdateCustomerCommand;

class CustomerCommandBusServiceProvider extends ServiceProvider
{
    public function boot(CommandBusInterface $commandBus): void
    {
        $commandBus->map([
            CreateCustomerCommand::class => CreateCustomerCommandHandler::class,
            UpdateCustomerCommand::class => UpdateCustomerCommandHandler::class,
        ]);
    }
}
