<?php

declare(strict_types=1);

namespace App\V1\Core;

use App\V1\Core\Application\Providers\Core\CoreBladeServiceProvider;
use App\V1\Core\Application\Providers\Core\CoreConfigServiceProvider;
use App\V1\Core\Application\Providers\Core\CoreEventServiceProvider;
use App\V1\Core\Application\Providers\Core\CoreMigrationStubsServiceProvider;
use App\V1\Core\Application\Providers\Core\CoreRouteServiceProvider;
use App\V1\Core\Application\Providers\Core\CoreViewServiceProvider;
use App\V1\Core\Application\Providers\CoreServiceProvider;
use App\V1\Core\Application\Providers\Extenders\ViteHmrServiceProvider;
use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Core\Infrastructure\Packages\Sanctum\Providers\SanctumServiceProvider;
use App\V1\Core\Infrastructure\Packages\Telescope\Providers\TelescopeServiceProvider;

class CoreModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'core';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(CoreServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
        $this->app->register(CoreConfigServiceProvider::class);
        $this->app->register(CoreMigrationStubsServiceProvider::class);
        $this->app->register(CoreViewServiceProvider::class);
        $this->app->register(CoreBladeServiceProvider::class);
        $this->app->register(CoreEventServiceProvider::class);
        $this->app->register(CoreRouteServiceProvider::class);
        $this->app->register(ViteHmrServiceProvider::class);
        $this->app->register(SanctumServiceProvider::class);
    }
}
