<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\Auth\Application\Commands\ResetPasswordCommand;
use App\V1\Modules\Auth\Application\Commands\SetNewPasswordCommand;
use App\V1\Modules\User\Domain\Aggregates\UserAggregate;
use App\V1\Modules\User\Domain\Models\User;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Throwable;

readonly class ResetPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CommandBusInterface    $commandBus,
        private UserRepository         $userRepository,
        private PasswordBrokerContract $passwordBroker,
        private UserAggregate          $userAggregate
    ) {
    }

    /**
     * @param  ResetPasswordCommand $command
     * @throws Throwable
     */
    public function handle(CommandInterface $command): void
    {
        $user = $this->userRepository->findByRememberToken($command->remember_token);

        $status = $this->passwordBroker->reset(
            [
                'email' => $user->getEmailForPasswordReset(),
                'password' => $command->password,
                'token' => $command->token,
            ],
            fn (User $user, $password) => $this->commandBus->dispatch(
                new SetNewPasswordCommand(
                    $user->id,
                    $user->getRememberToken(),
                    $password
                )
            )
        );

        $this->userAggregate->verifyResetPasswordStatus($status);
    }
}
