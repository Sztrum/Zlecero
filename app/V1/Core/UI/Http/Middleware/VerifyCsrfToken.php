<?php

declare(strict_types=1);

namespace App\V1\Core\UI\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'api/v1/configurations/*/variants/*/personalization/*',
    ];
}
