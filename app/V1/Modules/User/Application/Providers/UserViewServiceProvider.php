<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Application\Providers;

use App\V1\Core\Application\Providers\ViewServiceProvider;

class UserViewServiceProvider extends ViewServiceProvider
{
    public const MODULE_NAME = 'user';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }
}
