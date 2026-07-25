<?php

declare(strict_types=1);

namespace App\V1\Modules\StaticPages\Application\Providers;

use App\V1\Core\Application\Providers\ViewServiceProvider;

class StaticPagesViewServiceProvider extends ViewServiceProvider
{
    public const MODULE_NAME = 'static_pages';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }
}
