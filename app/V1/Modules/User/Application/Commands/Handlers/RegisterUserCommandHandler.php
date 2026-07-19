<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\User\Application\Commands\RegisterUserCommand;
use App\V1\Modules\User\Domain\Aggregates\UserAggregate;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Throwable;

readonly class RegisterUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepository   $userRepository,
        private UserAggregate    $userAggregate
    ) {
    }

    /**
     * @param  RegisterUserCommand $command
     * @throws Throwable
     */
    public function handle(CommandInterface $command): void
    {
        $this->userAggregate->checkUserWithEmailAlreadyExist($command->email);

        $this->userRepository->create([
            'name' => $command->name,
            'email' => $command->email,
            'password' => Str::random(36),
            'remember_token' => Uuid::uuid4(),
        ]);
    }
}
