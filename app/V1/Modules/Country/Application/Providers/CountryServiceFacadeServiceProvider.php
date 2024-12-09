<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Application\Providers;

use App\V1\Modules\Country\Domain\Services\CountryService;
use App\V1\Modules\Country\Infrastructure\Mappers\CountryEntityMapper;
use Illuminate\Support\ServiceProvider;

class CountryServiceFacadeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('countryservice', function () {
            return new CountryService(
                new CountryEntityMapper(),
                app('config'),
            );
        });
    }
}
