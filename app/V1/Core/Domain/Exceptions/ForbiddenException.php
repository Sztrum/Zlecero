<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ForbiddenException extends HttpException
{
    /**
     * @param array<string, string|string[]> $headers
     */
    public function __construct(
        string $message = '',
        public int $statusCode = Response::HTTP_FORBIDDEN,
        ?Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        if (!$message) {
            $message = __('core::messages.forbidden');
        }

        parent::__construct($message, $statusCode, $previous, $headers, $code);
    }
}
