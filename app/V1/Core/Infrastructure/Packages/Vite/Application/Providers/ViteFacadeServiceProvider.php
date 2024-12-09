<?php

declare(strict_types=1);

namespace App\V1\Core\Infrastructure\Packages\Vite\Application\Providers;

use App\V1\Core\Infrastructure\Packages\Vite\Domain\Abstracts\AbstractVite;
use Illuminate\Support\ServiceProvider;

class ViteFacadeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('vite', function () {
            return new AbstractVite();
        });
    }
}
