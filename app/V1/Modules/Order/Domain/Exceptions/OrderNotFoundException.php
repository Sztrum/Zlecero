<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class OrderNotFoundException extends DomainException
{
    /**
     * @param array<string, bool|float|int|string|null> $replace
     */
    public function __construct(string $message = '', array $replace = [])
    {
        if ($message === '') {
            $message = 'order::_backend/exceptions.order_not_found';
        }

        parent::__construct($message, $replace);
    }
}
