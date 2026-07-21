<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Exceptions;

class InvalidEmailException extends DomainException
{
    /**
     * @param  array<string, bool|float|int|string|null>  $replace
     */
    public function __construct(
        string $message = '',
        array $replace = []
    ) {
        if ($message === '') {
            $message = 'core::_backend/exceptions.invalid_email';
        }

        parent::__construct($message, $replace);
    }
}
