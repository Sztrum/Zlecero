<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withSingletons([
        Illuminate\Contracts\Console\Kernel::class => \App\V1\Core\UI\CLI\Kernel::class,
        Illuminate\Contracts\Debug\ExceptionHandler::class => \App\V1\Core\Domain\Exceptions\ExceptionHandler::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            \App\V1\Core\UI\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\V1\Core\UI\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\V1\Core\UI\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        $middleware->group('web', [
            \App\V1\Core\UI\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\V1\Core\UI\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->priority([
            \App\V1\Core\UI\Http\Middleware\ForceJsonResponse::class,
        ]);

        $middleware->group('api', [
            'throttle:api',
            \App\V1\Core\UI\Http\Middleware\ForceJsonResponse::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\V1\Core\UI\Http\Middleware\SetAppLocaleByAcceptLanguageHeader::class,
            'auth:sanctum',
        ]);

        $middleware->alias([
            'auth' => \App\V1\Core\UI\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\V1\Core\UI\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \App\V1\Core\UI\Http\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
