<?php

declare(strict_types=1);

namespace App\V1\Core\UI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetAppLocaleByAcceptLanguageHeader
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Language', 'pl');

        if (in_array($locale, config('core::languages.enabled_system_languages'))) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
