<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\Auth\Application\Commands\SetUserRememberTokenCommand;
use App\V1\Modules\User\Domain\Aggregates\UserAggregate;
use App\V1\Modules\User\Domain\Models\User;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Throwable;

readonly class SetUserRememberTokenCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserAggregate  $userAggregate,
    ) {
    }

    /**
     * @param  SetUserRememberTokenCommand $command
     * @throws Throwable
     */
    public function handle(CommandInterface $command): void
    {
        /** @var User $user */
        $user = $this->userRepository->findById($command->user_id);

        $this->userAggregate->validateVerifiedEmail($user);

        $this->userRepository->update($user, [
            'remember_token' => $command->rememberToken,
        ]);
    }
}
