<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Application\Commands;

use App\V1\Core\Application\Command\CommandTransactionalInterface;
use App\V1\Modules\User\Application\Commands\Handlers\RegisterUserCommandHandler;

/**
 * @see RegisterUserCommandHandler
 */
readonly class RegisterUserCommand implements CommandTransactionalInterface
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $companyName,
    ) {
    }
}
