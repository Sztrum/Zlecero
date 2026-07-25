<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\Application\Providers;

use App\V1\Core\Application\Providers\Routes\ApiRouteServiceProvider;
use App\V1\Modules\Inquiry\UI\Http\Controllers\ApiInquiryController;
use Illuminate\Contracts\Routing\Registrar;

class InquiryRouteServiceProvider extends ApiRouteServiceProvider
{
    protected function registerRoutes(Registrar $router): void
    {
        $router->get('/', [ApiInquiryController::class, 'index'])->name('index');
        $router->post('/', [ApiInquiryController::class, 'store'])->name('store');
        $router->get('/{inquiry_id}', [ApiInquiryController::class, 'show'])->name('show');
        $router->patch('/{inquiry_id}', [ApiInquiryController::class, 'update'])->name('update');
        $router->patch('/{inquiry_id}/status', [ApiInquiryController::class, 'changeStatus'])->name('change-status');
        $router->patch('/{inquiry_id}/archive', [ApiInquiryController::class, 'archive'])->name('archive');
        $router->patch('/{inquiry_id}/restore', [ApiInquiryController::class, 'restore'])->name('restore');
        $router->post('/{inquiry_id}/messages', [ApiInquiryController::class, 'storeMessage'])->name('messages.store');
    }
}
