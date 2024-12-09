<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Application\Providers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\Application\Providers\CommandBusServiceProvider;
use App\V1\Modules\User\Application\Commands\Handlers\RegisterUserCommandHandler;
use App\V1\Modules\User\Application\Commands\RegisterUserCommand;

class UserCommandBusServiceProvider extends CommandBusServiceProvider
{
    public function registerCommands(CommandBusInterface $commandBus): void
    {
        $commandBus->map([
            RegisterUserCommand::class => RegisterUserCommandHandler::class,
        ]);
    }
}
