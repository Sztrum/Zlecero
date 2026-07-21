<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\ForbiddenException;

class AuthException extends ForbiddenException
{
    /**
     * @param  array<string, bool|float|int|string|null>  $replace
     */
    public function __construct(
        string $message = '',
        array $replace = []
    ) {
        if ($message === '') {
            $message = 'auth::_backend/exceptions.auth_failed';
        }

        parent::__construct($message, $replace);
    }
}
