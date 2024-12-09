<?php

declare(strict_types=1);

namespace App\V1\Modules\Email;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\Company\Application\Providers\CompanyCommandBusServiceProvider;
use App\V1\Modules\Company\Application\Providers\CompanyEventServiceProvider;
use App\V1\Modules\Email\Application\Providers\EmailServiceProvider;

class EmailModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'emails';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(EmailServiceProvider::class);
        //        $this->app->register(CompanyCommandBusServiceProvider::class);
        //        $this->app->register(CompanyEventServiceProvider::class);
    }
}
