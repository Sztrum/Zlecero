<?php

declare(strict_types=1);

namespace App\V1\Modules\Order;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\Order\Application\Providers\OrderRouteServiceProvider;

class OrderModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'order';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(OrderRouteServiceProvider::class);
    }
}
