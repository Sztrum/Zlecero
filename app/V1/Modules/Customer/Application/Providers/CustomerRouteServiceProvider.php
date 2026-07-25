<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\Customer\UI\Http\Controllers\ApiCustomerController;
use Illuminate\Contracts\Routing\Registrar;

class CustomerRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/', [ApiCustomerController::class, 'index'])->name('index');
        $router->post('/', [ApiCustomerController::class, 'store'])->name('store');
        $router->get('/{customer_id}', [ApiCustomerController::class, 'show'])->name('show');
        $router->patch('/{customer_id}', [ApiCustomerController::class, 'update'])->name('update');
    }
}
