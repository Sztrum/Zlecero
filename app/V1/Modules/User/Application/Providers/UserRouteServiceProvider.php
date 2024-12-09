<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\User\UI\Http\Controllers\UserController;
use Illuminate\Contracts\Routing\Registrar;

class UserRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->group([
            'prefix' => '{user_id}',
        ], function (Registrar $router) {
            $router->get('/companies', [UserController::class, 'companies'])->name('companies');
            $router->get('/companies/invitations', [UserController::class, 'invitations'])->name('invitations');
        });
    }
}
