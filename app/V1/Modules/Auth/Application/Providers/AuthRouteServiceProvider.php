<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\Auth\UI\Http\Controllers\AuthController;
use Illuminate\Contracts\Routing\Registrar;

class AuthRouteServiceProvider extends ApiRouteServiceProvider
{
    protected bool $pluralPrefix = false;
    protected bool $pluralRouteName = false;

    protected function registerRoutes(Registrar $router): void
    {
        $router->group([
            'excluded_middleware' => ['auth:sanctum'],
        ], function (Registrar $router) {
            $router->post('/login', [AuthController::class, 'login'])->name('login');
            $router->post('/register', [AuthController::class, 'register'])->name('register');
            $router->post('/verify-email/{user_id}/email/verify/{hash}', [AuthController::class, 'verifyEmail'])->name('verify-email');
            $router->post('/user/{user_id}/new-password/{remember_token}', [AuthController::class, 'newPassword'])->name('new-password');
            $router->post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
            $router->post('/reset-password/{remember_token}', [AuthController::class, 'resetPassword'])->name('reset-password');
        });

        $router->get('/profile', [AuthController::class, 'profile'])->name('profile');
        $router->get('/logout', [AuthController::class, 'logout'])->name('logout');
    }
}
