<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\Offer\Application\Providers\OfferRouteServiceProvider;

class OfferModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'offer';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(OfferRouteServiceProvider::class);
    }
}
