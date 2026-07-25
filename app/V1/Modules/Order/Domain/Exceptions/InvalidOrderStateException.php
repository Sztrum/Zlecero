<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\ConflictException;

class InvalidOrderStateException extends ConflictException
{
    /**
     * @param array<string, bool|float|int|string|null> $replace
     */
    public function __construct(string $message = '', array $replace = [])
    {
        if ($message === '') {
            $message = 'order::_backend/exceptions.invalid_order_state';
        }

        parent::__construct($message, $replace);
    }
}
