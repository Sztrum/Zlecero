<?php

declare(strict_types=1);

namespace App\V1\Modules\Company;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\Company\Application\Providers\CompanyRouteServiceProvider;

class CompanyModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'company';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(CompanyRouteServiceProvider::class);
    }
}
