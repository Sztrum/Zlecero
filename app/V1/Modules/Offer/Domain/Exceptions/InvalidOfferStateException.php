<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\ConflictException;

class InvalidOfferStateException extends ConflictException
{
    /**
     * @param array<string, bool|float|int|string|null> $replace
     */
    public function __construct(string $message = '', array $replace = [])
    {
        if ($message === '') {
            $message = 'offer::_backend/exceptions.invalid_offer_state';
        }

        parent::__construct($message, $replace);
    }
}
