<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;

    public function dispatchMany(CommandInterface ...$commands): void;

    public function dispatchManyWithTransaction(CommandInterface ...$commands): void;

    public function map(array $map): void;
}
