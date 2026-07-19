<?php

declare(strict_types=1);

namespace App\V1\Core\UI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');
        $response = $next($request);

        if (!$response instanceof Response) {
            throw new RuntimeException('Middleware response must be a Symfony response.');
        }

        return $response;
    }
}
