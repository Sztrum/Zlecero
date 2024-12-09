<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\Auth\Application\Commands\ForgotPasswordCommand;
use App\V1\Modules\Auth\Application\Commands\SetUserRememberTokenCommand;
use App\V1\Modules\Email\Domain\Services\EmailService;
use App\V1\Modules\User\Domain\Aggregates\UserAggregate;
use App\V1\Modules\User\Domain\Mail\ResetPasswordMail;
use App\V1\Modules\User\Domain\Models\User;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Ramsey\Uuid\Uuid;
use Throwable;

readonly class ForgotPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CommandBusInterface     $commandBus,
        private UserAggregate           $userAggregate,
        private UserRepository          $userRepository,
        private PasswordBrokerContract  $passwordBroker,
        private EmailService $emailService,
    ) {
    }

    /**
     * @param  ForgotPasswordCommand $command
     * @throws Throwable
     */
    public function handle(CommandInterface $command): void
    {
        $this->userAggregate->checkUserWithEmailNotExist($command->email);

        $user = $this->userRepository->findByEmail($command->email);

        $this->commandBus->dispatch(
            new SetUserRememberTokenCommand($user->id, Uuid::uuid4()->toString())
        );

        $this->passwordBroker->sendResetLink(
            [
                'email' => $command->email,
            ],
            fn (User $user, string $token) => $this->emailService->sendEmail([$user->email], new ResetPasswordMail($user, $token))
        );
    }
}
