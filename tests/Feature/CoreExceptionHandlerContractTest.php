<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class CoreExceptionHandlerContractTest extends TestCase
{
    public function test_core_frontend_config_is_loaded_from_core_domain_config_provider(): void
    {
        $frontendUrl = $this->readStringEnvironmentValue('FRONTEND_URL');
        $frontendAppUrl = $this->readStringEnvironmentValue('FRONTEND_APP_URL');
        $appUrl = config('app.url');
        $expectedUrl = $frontendUrl
            ?? $frontendAppUrl
            ?? (is_string($appUrl) && $appUrl !== '' ? $appUrl : 'http://localhost');

        $this->assertSame($expectedUrl, config('core::frontend.url'));
    }

    public function test_web_request_uses_laravel_exception_rendering(): void
    {
        config(['app.debug' => true]);

        Route::get('/_test/web-exception-rendering', static function (): void {
            throw new RuntimeException('Original web failure.');
        });

        $response = $this->get('/_test/web-exception-rendering');

        $response->assertStatus(500);
        $this->assertStringContainsString('text/html', (string) $response->headers->get('content-type'));
        $this->assertStringContainsString('Original web failure.', (string) $response->getContent());
    }

    public function test_unwritable_log_channel_does_not_mask_original_exception_response(): void
    {
        config([
            'app.debug' => true,
            'logging.default' => 'stack',
            'logging.channels.stack.channels' => ['single'],
            'logging.channels.stack.ignore_exceptions' => true,
            'logging.channels.single.path' => '/proc/zlecero/laravel.log',
        ]);

        $this->app['log']->forgetChannel('stack');
        $this->app['log']->forgetChannel('single');

        Route::get('/_test/unwritable-log-channel', static function (): void {
            throw new RuntimeException('Original visible failure.');
        });

        $this->getJson('/_test/unwritable-log-channel')
            ->assertStatus(500)
            ->assertJsonPath('message', 'Original visible failure.')
            ->assertJsonPath('data.exception', RuntimeException::class);
    }

    public function test_database_exception_code_does_not_mask_original_json_response(): void
    {
        config(['app.debug' => true]);

        Route::get('/_test/database-exception-code', static function (): void {
            DB::select('select FIELD(1)');
        });

        $this->getJson('/_test/database-exception-code')
            ->assertStatus(500)
            ->assertJsonPath('status', 500)
            ->assertJsonPath('data.exception', 'Illuminate\Database\QueryException');
    }

    private function readStringEnvironmentValue(string $key): ?string
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        return is_string($value) && $value !== '' ? $value : null;
    }
}
