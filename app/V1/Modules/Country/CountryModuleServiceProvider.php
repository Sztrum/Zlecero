<?php

declare(strict_types=1);

namespace App\V1\Modules\Country;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\Country\Application\Providers\CountryConfigServiceProvider;
use App\V1\Modules\Country\Application\Providers\CountryRouteServiceProvider;
use App\V1\Modules\Country\Application\Providers\CountryServiceFacadeServiceProvider;

class CountryModuleServiceProvider extends ModuleServiceProvider
{
    public const string MODULE_NAME = 'countries';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(CountryConfigServiceProvider::class);
        $this->app->register(CountryServiceFacadeServiceProvider::class);
        $this->app->register(CountryRouteServiceProvider::class);
    }
}
