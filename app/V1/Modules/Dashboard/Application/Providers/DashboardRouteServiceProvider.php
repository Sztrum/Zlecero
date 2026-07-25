<?php

declare(strict_types=1);

namespace App\V1\Modules\Dashboard\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\Dashboard\UI\Http\Controllers\ApiDashboardController;
use Illuminate\Contracts\Routing\Registrar;

class DashboardRouteServiceProvider extends ApiRouteServiceProvider
{
    protected bool $pluralPrefix = false;

    protected bool $pluralRouteName = false;

    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/', [ApiDashboardController::class, 'company'])->name('company');
        $router->get('/admin', [ApiDashboardController::class, 'admin'])->name('admin');
    }
}
