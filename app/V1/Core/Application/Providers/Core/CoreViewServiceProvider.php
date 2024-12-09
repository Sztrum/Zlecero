<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Core;

use App\V1\Core\Application\Providers\ViewServiceProvider;
use Illuminate\Foundation\Application;

class CoreViewServiceProvider extends ViewServiceProvider
{
    public const MODULE_NAME = 'core';
    protected const VIEWS_PATH = '../../../UI/Http/Views';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function boot(Application $application): void
    {
    }
}
