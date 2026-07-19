<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Facades;

use Illuminate\Support\Facades\Facade;

class CountryServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'countryservice';
    }
}
