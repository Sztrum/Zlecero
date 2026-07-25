<?php

declare(strict_types=1);

namespace App\V1\Modules\StaticPages;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\StaticPages\Application\Providers\StaticPagesRouteServiceProvider;
use App\V1\Modules\StaticPages\Application\Providers\StaticPagesViewServiceProvider;

class StaticPagesModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'static_pages';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(StaticPagesViewServiceProvider::class);
        $this->app->register(StaticPagesRouteServiceProvider::class);
    }
}
