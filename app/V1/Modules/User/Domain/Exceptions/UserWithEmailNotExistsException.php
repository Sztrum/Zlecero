<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class UserWithEmailNotExistsException extends DomainException
{
    /**
     *  array<string, bool|float|int|string|null> $replace
     */
    public function __construct(
        string $message = '',
        array $replace = []
    ) {
        if ($message === '') {
            $message = __('user::exceptions.other.user_with_email_not_exists', $replace);
        }

        parent::__construct($message, $replace);
    }
}
