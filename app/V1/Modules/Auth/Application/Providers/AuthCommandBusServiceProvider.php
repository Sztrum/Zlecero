<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Application\Providers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\Application\Providers\CommandBusServiceProvider;
use App\V1\Modules\Auth\Application\Commands\ForgotPasswordCommand;
use App\V1\Modules\Auth\Application\Commands\Handlers\ForgotPasswordCommandHandler;
use App\V1\Modules\Auth\Application\Commands\Handlers\ResetPasswordCommandHandler;
use App\V1\Modules\Auth\Application\Commands\Handlers\SetNewPasswordCommandHandler;
use App\V1\Modules\Auth\Application\Commands\Handlers\SetUserRememberTokenCommandHandler;
use App\V1\Modules\Auth\Application\Commands\Handlers\VerifyUserEmailCommandHandler;
use App\V1\Modules\Auth\Application\Commands\ResetPasswordCommand;
use App\V1\Modules\Auth\Application\Commands\SetNewPasswordCommand;
use App\V1\Modules\Auth\Application\Commands\SetUserRememberTokenCommand;
use App\V1\Modules\Auth\Application\Commands\VerifyUserEmailCommand;

class AuthCommandBusServiceProvider extends CommandBusServiceProvider
{
    public function registerCommands(CommandBusInterface $commandBus): void
    {
        $commandBus->map([
            VerifyUserEmailCommand::class => VerifyUserEmailCommandHandler::class,
            SetUserRememberTokenCommand::class => SetUserRememberTokenCommandHandler::class,
            SetNewPasswordCommand::class => SetNewPasswordCommandHandler::class,
            ForgotPasswordCommand::class => ForgotPasswordCommandHandler::class,
            ResetPasswordCommand::class => ResetPasswordCommandHandler::class,
        ]);
    }
}
