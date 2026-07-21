<?php

declare(strict_types=1);

namespace App\V1\Modules\StaticPages\Application\Providers;

use App\V1\Core\Application\Providers\ViewServiceProvider;
use App\V1\Modules\StaticPages\StaticPagesModuleServiceProvider;

class StaticPagesViewServiceProvider extends ViewServiceProvider
{
    public function moduleName(): string
    {
        return StaticPagesModuleServiceProvider::MODULE_NAME;
    }
}
