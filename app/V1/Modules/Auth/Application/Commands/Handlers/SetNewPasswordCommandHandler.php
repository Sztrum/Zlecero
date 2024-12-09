<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\Auth\Application\Commands\SetNewPasswordCommand;
use App\V1\Modules\User\Domain\Aggregates\UserAggregate;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Throwable;

readonly class SetNewPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserAggregate  $userAggregate,
        private Hasher         $hasher
    ) {
    }

    /**
     * @param  SetNewPasswordCommand $command
     * @throws Throwable
     */
    public function handle(CommandInterface $command): void
    {
        $user = $this->userRepository->findByIdAndRememberToken(
            user_id: $command->user_id,
            remember_token: $command->remember_token
        );

        $this->userAggregate->validateVerifiedEmail($user);

        $this->userRepository->update($user, [
            'password' => $this->hasher->make($command->password),
            'remember_token' => null,
        ]);
    }
}
