<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Extenders;

use App\V1\Core\Infrastructure\Packages\Vite\Domain\Abstracts\AbstractVite;
use Illuminate\Foundation\Vite;
use Illuminate\Support\ServiceProvider;

class ViteHmrServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->app->bind(Vite::class, function () {
            return new AbstractVite();
        });
    }
}
