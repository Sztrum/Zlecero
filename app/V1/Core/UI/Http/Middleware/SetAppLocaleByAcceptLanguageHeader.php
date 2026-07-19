<?php

declare(strict_types=1);

namespace App\V1\Core\UI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class SetAppLocaleByAcceptLanguageHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Accept-Language', 'pl');
        $enabledLanguages = config('core::languages.enabled_system_languages');

        if (!is_array($enabledLanguages)) {
            throw new RuntimeException('Config core::languages.enabled_system_languages must be an array.');
        }

        if (in_array($locale, $enabledLanguages, true)) {
            App::setLocale($locale);
        }

        $response = $next($request);

        if (!$response instanceof Response) {
            throw new RuntimeException('Middleware response must be a Symfony response.');
        }

        return $response;
    }
}
