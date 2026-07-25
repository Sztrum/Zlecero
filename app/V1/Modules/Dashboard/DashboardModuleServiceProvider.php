<?php

declare(strict_types=1);

namespace App\V1\Modules\Dashboard;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\Dashboard\Application\Providers\DashboardRouteServiceProvider;

class DashboardModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'dashboard';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(DashboardRouteServiceProvider::class);
    }
}
