<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Core;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use RuntimeException;

class CoreRouteServiceProvider extends ApiRouteServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    protected function registerRoutes(Registrar $router): void {}

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $configuredMaxAttempts = config('auth.throttle.default', 500);

            if (! is_numeric($configuredMaxAttempts)) {
                throw new RuntimeException('Config auth.throttle.default must be numeric.');
            }

            $maxAttempts = (int) $configuredMaxAttempts;

            if ($maxAttempts <= 0) {
                throw new RuntimeException('Config auth.throttle.default must be greater than zero.');
            }

            $user = $request->user();

            return Limit::perMinute($maxAttempts)->by($user?->id ?: $request->ip());
        });
    }
}
