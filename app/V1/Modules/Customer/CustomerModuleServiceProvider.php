<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\Customer\Application\Providers\CustomerCommandBusServiceProvider;
use App\V1\Modules\Customer\Application\Providers\CustomerRouteServiceProvider;

class CustomerModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'customer';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(CustomerCommandBusServiceProvider::class);
        $this->app->register(CustomerRouteServiceProvider::class);
    }
}
