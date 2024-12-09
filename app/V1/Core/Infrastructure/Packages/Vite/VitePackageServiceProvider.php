<?php

declare(strict_types=1);

namespace App\V1\Core\Infrastructure\Packages\Vite;

use App\V1\Core\Application\Providers\ServiceProvider;
use App\V1\Core\Infrastructure\Packages\Vite\Application\Providers\ViteFacadeServiceProvider;

class VitePackageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->register(ViteFacadeServiceProvider::class);
    }
}
