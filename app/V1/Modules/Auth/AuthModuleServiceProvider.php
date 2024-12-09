<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\Auth\Application\Providers\AuthCommandBusServiceProvider;
use App\V1\Modules\Auth\Application\Providers\AuthRouteServiceProvider;

class AuthModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'auth';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(AuthCommandBusServiceProvider::class);
        $this->app->register(AuthRouteServiceProvider::class);
    }
}
