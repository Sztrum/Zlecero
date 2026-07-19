<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Command\Facades;

use App\V1\Core\Application\Command\CommandBusInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin CommandBusInterface
 */
final class CommandBusFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CommandBusInterface::class;
    }
}
