<?php

declare(strict_types=1);

namespace App\V1\Core\Infrastructure\Packages\Vite\Domain\Abstracts;

use Illuminate\Support\Facades\Facade;

class AbstractViteFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vite';
    }
}
