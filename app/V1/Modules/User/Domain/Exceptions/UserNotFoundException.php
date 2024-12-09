<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class UserNotFoundException extends DomainException
{
    public function __construct(
        string $message = '',
        array $replace = []
    ) {
        if ($message === '') {
            $message = __('user::exceptions.other.user_not_found', $replace);
        }

        parent::__construct($message, $replace);
    }
}
