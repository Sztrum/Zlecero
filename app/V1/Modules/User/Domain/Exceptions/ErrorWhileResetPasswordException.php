<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class ErrorWhileResetPasswordException extends DomainException
{
    /**
     * @param  array<string, bool|float|int|string|null>  $replace
     */
    public function __construct(
        string $message = '',
        array $replace = []
    ) {
        if ($message === '') {
            $message = 'user::_backend/exceptions.error_while_reset_password';
        }

        parent::__construct($message, $replace);
    }
}
