<?php

use App\V1\Core\CoreModuleServiceProvider;
use App\V1\Core\Infrastructure\Packages\Vite\VitePackageServiceProvider;
use App\V1\Modules\Auth\AuthModuleServiceProvider;
use App\V1\Modules\Company\CompanyModuleServiceProvider;
use App\V1\Modules\Country\CountryModuleServiceProvider;
use App\V1\Modules\Customer\CustomerModuleServiceProvider;
use App\V1\Modules\Dashboard\DashboardModuleServiceProvider;
use App\V1\Modules\Email\EmailModuleServiceProvider;
use App\V1\Modules\Inquiry\InquiryModuleServiceProvider;
use App\V1\Modules\Offer\OfferModuleServiceProvider;
use App\V1\Modules\Order\OrderModuleServiceProvider;
use App\V1\Modules\StaticPages\StaticPagesModuleServiceProvider;
use App\V1\Modules\User\UserModuleServiceProvider;

return [
    CoreModuleServiceProvider::class,
    VitePackageServiceProvider::class,
    AuthModuleServiceProvider::class,
    CompanyModuleServiceProvider::class,
    CustomerModuleServiceProvider::class,
    DashboardModuleServiceProvider::class,
    InquiryModuleServiceProvider::class,
    OfferModuleServiceProvider::class,
    OrderModuleServiceProvider::class,
    StaticPagesModuleServiceProvider::class,
    UserModuleServiceProvider::class,
    CountryModuleServiceProvider::class,
    EmailModuleServiceProvider::class,
];
