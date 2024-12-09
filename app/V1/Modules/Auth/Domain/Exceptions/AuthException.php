<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\ForbiddenException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthException extends ForbiddenException
{
    public function __construct(
        string $message = null,
        public int $statusCode = Response::HTTP_FORBIDDEN,
        Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        if (!$message) {
            $message = __('auth::messages.auth_failed');
        }

        parent::__construct($message, $statusCode, $previous, $headers, $code);
    }
}
