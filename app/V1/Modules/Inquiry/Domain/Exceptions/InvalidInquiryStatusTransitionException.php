<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\ConflictException;

class InvalidInquiryStatusTransitionException extends ConflictException
{
    /**
     * @param array<string, bool|float|int|string|null> $replace
     */
    public function __construct(string $message = '', array $replace = [])
    {
        if ($message === '') {
            $message = 'inquiry::_backend/exceptions.invalid_status_transition';
        }

        parent::__construct($message, $replace);
    }
}
