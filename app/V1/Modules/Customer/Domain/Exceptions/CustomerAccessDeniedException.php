<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\ForbiddenException;

class CustomerAccessDeniedException extends ForbiddenException
{
    /**
     * @param array<string, bool|float|int|string|null> $replace
     */
    public function __construct(string $message = '', array $replace = [])
    {
        if ($message === '') {
            $message = 'customer::_backend/exceptions.customer_access_denied';
        }

        parent::__construct($message, $replace);
    }
}
