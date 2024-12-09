<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Core;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class CoreRouteServiceProvider extends ApiRouteServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    protected function registerRoutes(Registrar $router): void
    {
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $maxAttempts = (int) config('auth.throttle.default', 500);
            $user = $request->user();

            return Limit::perMinute($maxAttempts)->by($user?->id ?: $request->ip());
        });
    }
}
