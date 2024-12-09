<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Command\Adapters;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Core\Application\Command\CommandTransactionalInterface;
use App\V1\Core\Application\Command\Pipes\TransactionPipe;
use Illuminate\Contracts\Bus\Dispatcher as DispatcherContract;
use Illuminate\Database\DatabaseManager;
use Throwable;

class LaravelCommandBus implements CommandBusInterface
{
    public function __construct(
        private DispatcherContract $bus,
        private DatabaseManager $databaseManager,
    ) {
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->bus->pipeThrough(
            $this->commandShouldUseTransaction($command)
                ? [TransactionPipe::class]
                : []
        )->dispatch($command);
    }

    public function dispatchMany(CommandInterface ...$commands): void
    {
        foreach ($commands as $command) {
            $this->dispatch($command);
        }
    }

    /**
     * @throws Throwable
     */
    public function dispatchManyWithTransaction(CommandInterface ...$commands): void
    {
        $connection = $this->databaseManager->connection();

        try {
            $connection->beginTransaction();

            $this->dispatchMany(...$commands);

            $connection->commit();
        } catch (Throwable $exception) {
            $connection->rollBack();

            throw $exception;
        }
    }

    public function map(array $map): void
    {
        $this->bus->map($map);
    }

    protected function commandShouldUseTransaction(CommandInterface $command): bool
    {
        return $command instanceof CommandTransactionalInterface;
    }
}
