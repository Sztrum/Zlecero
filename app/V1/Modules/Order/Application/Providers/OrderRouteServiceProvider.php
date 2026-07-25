<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\Order\UI\Http\Controllers\ApiOrderController;
use Illuminate\Contracts\Routing\Registrar;

class OrderRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/', [ApiOrderController::class, 'index'])->name('index');
        $router->get('/{order_id}', [ApiOrderController::class, 'show'])->name('show');
    }
}
