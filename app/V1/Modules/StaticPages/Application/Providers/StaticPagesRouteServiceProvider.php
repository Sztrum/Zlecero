<?php

declare(strict_types=1);

namespace App\V1\Modules\StaticPages\Application\Providers;

use App\V1\Core\Application\Providers\Routes\FrontendRouteServiceProvider;
use App\V1\Modules\StaticPages\UI\Http\Controllers\FrontStaticPagesController;
use Illuminate\Contracts\Routing\Registrar;

class StaticPagesRouteServiceProvider extends FrontendRouteServiceProvider
{
    protected bool $prefix = false;

    protected bool $pluralRouteName = false;

    protected string $prefixRouteName = 'front.';

    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/', [FrontStaticPagesController::class, 'redirectHome'])->name('home.redirect');
        $router->get('/login', [FrontStaticPagesController::class, 'redirectLogin'])->name('auth.login.redirect');
        $router->get('/auth/register', [FrontStaticPagesController::class, 'redirectRegister'])->name('auth.register.redirect');
        $router->get('/{locale}', [FrontStaticPagesController::class, 'landing'])
            ->whereIn('locale', FrontStaticPagesController::LOCALES)
            ->name('home');
        $router->get('/{locale}/pricing', [FrontStaticPagesController::class, 'pricing'])
            ->whereIn('locale', FrontStaticPagesController::LOCALES)
            ->name('pricing');
        $router->get('/{locale}/faq', [FrontStaticPagesController::class, 'faq'])
            ->whereIn('locale', FrontStaticPagesController::LOCALES)
            ->name('faq');
        $router->get('/{locale}/about', [FrontStaticPagesController::class, 'about'])
            ->whereIn('locale', FrontStaticPagesController::LOCALES)
            ->name('about');
        $router->get('/{locale}/contact', [FrontStaticPagesController::class, 'contact'])
            ->whereIn('locale', FrontStaticPagesController::LOCALES)
            ->name('contact');
        $router->post('/{locale}/contact', [FrontStaticPagesController::class, 'submitContact'])
            ->whereIn('locale', FrontStaticPagesController::LOCALES)
            ->name('contact.submit');
    }
}
