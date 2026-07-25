<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\Offer\UI\Http\Controllers\ApiOfferController;
use Illuminate\Contracts\Routing\Registrar;

class OfferRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/', [ApiOfferController::class, 'index'])->name('index');
        $router->post('/', [ApiOfferController::class, 'store'])->name('store');
        $router->get('/{offer_id}', [ApiOfferController::class, 'show'])->name('show');
        $router->patch('/{offer_id}', [ApiOfferController::class, 'update'])->name('update');
        $router->patch('/{offer_id}/send', [ApiOfferController::class, 'send'])->name('send');
        $router->post('/{offer_id}/pdf', [ApiOfferController::class, 'generatePdf'])->name('pdf.generate');
        $router->get('/{offer_id}/pdf/download', [ApiOfferController::class, 'downloadPdf'])->name('pdf.download');
        $router->post('/{offer_id}/accept', [ApiOfferController::class, 'accept'])->name('accept');
    }
}
