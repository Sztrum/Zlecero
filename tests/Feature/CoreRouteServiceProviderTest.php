<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use RuntimeException;
use Tests\TestCase;

class CoreRouteServiceProviderTest extends TestCase
{
    public function test_api_limiter_accepts_numeric_string_max_attempts_config(): void
    {
        config(['auth.throttle.default' => '60']);

        $limit = $this->resolveApiLimit();

        $this->assertSame(60, $limit->maxAttempts);
    }

    public function test_api_limiter_rejects_non_positive_max_attempts_config(): void
    {
        config(['auth.throttle.default' => '0']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Config auth.throttle.default must be greater than zero.');

        $this->resolveApiLimit();
    }

    private function resolveApiLimit(): Limit
    {
        $limiter = RateLimiter::limiter('api');

        $this->assertNotNull($limiter);

        $limit = $limiter(Request::create('/api/test'));

        $this->assertInstanceOf(Limit::class, $limit);

        return $limit;
    }
}
