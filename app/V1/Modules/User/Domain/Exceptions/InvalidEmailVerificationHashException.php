<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class InvalidEmailVerificationHashException extends DomainException
{
    /**
     *  array<string, bool|float|int|string|null> $replace
     */
    public function __construct(
        string $message = '',
        array $replace = []
    ) {
        if ($message === '') {
            $message = __('user::exceptions.other.invalid_email_verification_hash', $replace);
        }

        parent::__construct($message, $replace);
    }
}
