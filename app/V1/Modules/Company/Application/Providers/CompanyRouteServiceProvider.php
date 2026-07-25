<?php

declare(strict_types=1);

namespace App\V1\Modules\Company\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\Company\UI\Http\Controllers\ApiCompanyController;
use App\V1\Modules\User\UI\Http\Controllers\ApiCompanyUserController;
use Illuminate\Contracts\Routing\Registrar;

class CompanyRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/current', [ApiCompanyController::class, 'show'])->name('current');
        $router->patch('/current', [ApiCompanyController::class, 'update'])->name('update-current');
        $router->get('/users', [ApiCompanyUserController::class, 'index'])->name('users.index');
        $router->post('/users', [ApiCompanyUserController::class, 'store'])->name('users.store');
        $router->patch('/users/{user_id}/deactivate', [ApiCompanyUserController::class, 'deactivate'])->name('users.deactivate');
    }
}
