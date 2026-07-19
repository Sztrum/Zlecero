<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Command\Abstracts;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;

abstract readonly class BaseCommandHandler implements CommandHandlerInterface
{
    abstract public function handle(CommandInterface $command): void;
}
