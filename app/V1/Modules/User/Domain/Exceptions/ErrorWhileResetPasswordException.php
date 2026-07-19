<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class ErrorWhileResetPasswordException extends DomainException
{
    /**
     *  array<string, bool|float|int|string|null> $replace
     */
    public function __construct(
        string $message = '',
        array $replace = []
    ) {
        if ($message === '') {
            $message = __('user::exceptions.other.error_while_reset_password', $replace);
        }

        parent::__construct($message, $replace);
    }
}
