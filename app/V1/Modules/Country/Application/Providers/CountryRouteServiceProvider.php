<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\Country\UI\Http\Controllers\CountryController;
use Illuminate\Contracts\Routing\Registrar;

class CountryRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/', [CountryController::class, 'index'])->name('index');
    }
}
