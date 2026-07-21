<?php

declare(strict_types=1);

namespace App\V1\Modules\StaticPages\Application\Providers;

use App\V1\Core\Application\Providers\Routes\FrontendRouteServiceProvider;
use App\V1\Modules\StaticPages\UI\Http\Controllers\FrontStaticPageController;
use Illuminate\Contracts\Routing\Registrar;

class StaticPagesRouteServiceProvider extends FrontendRouteServiceProvider
{
    protected bool $prefix = false;

    protected bool $pluralRouteName = false;

    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/', [FrontStaticPageController::class, 'home'])->name('home');
    }
}
