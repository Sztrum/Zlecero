<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use App\V1\Modules\Inquiry\Application\Providers\InquiryRouteServiceProvider;

class InquiryModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'inquiry';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(InquiryRouteServiceProvider::class);
    }
}
