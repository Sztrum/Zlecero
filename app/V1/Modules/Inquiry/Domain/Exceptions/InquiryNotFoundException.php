<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class InquiryNotFoundException extends DomainException
{
    /**
     * @param array<string, bool|float|int|string|null> $replace
     */
    public function __construct(string $message = '', array $replace = [])
    {
        if ($message === '') {
            $message = 'inquiry::_backend/exceptions.inquiry_not_found';
        }

        parent::__construct($message, $replace);
    }
}
