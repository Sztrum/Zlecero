<?php

declare(strict_types=1);

namespace App\V1\Modules\User;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\User\Application\Providers\UserCommandBusServiceProvider;
use App\V1\Modules\User\Application\Providers\UserEventServiceProvider;
use App\V1\Modules\User\Application\Providers\UserViewServiceProvider;

class UserModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'user';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(UserCommandBusServiceProvider::class);
        $this->app->register(UserEventServiceProvider::class);
        $this->app->register(UserViewServiceProvider::class);
    }
}
