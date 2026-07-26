<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\Auth\Application\Commands\VerifyUserEmailCommand;
use App\V1\Modules\User\Domain\Aggregates\UserAggregate;
use App\V1\Modules\User\Domain\Models\User;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Throwable;

readonly class VerifyUserEmailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserAggregate  $userAggregate,
    ) {
    }

    /**
     * @param  VerifyUserEmailCommand $command
     * @throws Throwable
     */
    public function handle(CommandInterface $command): void
    {
        /** @var User $user */
        $user = $this->userRepository->findById($command->user_id);

        $this->userAggregate->verifyEmailVerificationHash($user, $command->hash);

        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->markEmailAsVerified();
    }
}
