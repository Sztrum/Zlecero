<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Application\Commands;

use App\V1\Core\Application\Command\CommandTransactionalInterface;

/**
 * @see VerifyUserEmailCommandHandler
 */
readonly class VerifyUserEmailCommand implements CommandTransactionalInterface
{
    public function __construct(
        public string $user_id,
        public string $hash
    ) {
    }
}
