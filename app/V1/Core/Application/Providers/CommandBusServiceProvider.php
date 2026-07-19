<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers;

use App\V1\Core\Application\Command\CommandBusInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * @method void registerCommands(CommandBusInterface $commandBus)
 */
abstract class CommandBusServiceProvider extends BaseServiceProvider
{
    protected array $commands = [];

    /**
     * @throws BindingResolutionException;
     */
    public function boot(): void
    {
        $commandBus = $this->app->make(CommandBusInterface::class);
        $commandBus->map($this->commands);

        if (method_exists($this, 'registerCommands')) {
            $this->app->call([$this, 'registerCommands'], compact('commandBus'));
        }
    }
}
