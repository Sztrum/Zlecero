<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ForbiddenException extends HttpException
{
    /**
     * @param  array<string, bool|float|int|string|null>  $replace
     * @param  array<string, string|string[]>  $headers
     */
    public function __construct(
        string $message = '',
        array $replace = [],
        public int $statusCode = Response::HTTP_FORBIDDEN,
        ?Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        if ($message === '') {
            $message = 'core::messages.forbidden';
        }

        parent::__construct(__($message, $replace), $statusCode, $previous, $headers, $code);
    }
}
