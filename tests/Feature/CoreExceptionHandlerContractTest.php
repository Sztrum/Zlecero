<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use RuntimeException;
use Tests\TestCase;

class CoreExceptionHandlerContractTest extends TestCase
{
    public function test_core_frontend_config_is_loaded_from_core_domain_config_provider(): void
    {
        $this->assertSame('http://localhost:5173', config('core::frontend.url'));
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
}
