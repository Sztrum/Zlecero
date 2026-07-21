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
        $router->get('/', [FrontStaticPageController::class, 'redirectToPreferredLocale'])
            ->name('home.redirect');

        $router->get('/{locale}', [FrontStaticPageController::class, 'home'])
            ->whereIn('locale', $this->enabledLocales())
            ->name('home');
    }

    /**
     * @return list<string>
     */
    private function enabledLocales(): array
    {
        $enabledLocales = config('core::languages.enabled_system_languages', ['pl', 'en', 'de']);

        if (!is_array($enabledLocales)) {
            return ['pl', 'en', 'de'];
        }

        return array_values(array_filter($enabledLocales, is_string(...)));
    }
}
