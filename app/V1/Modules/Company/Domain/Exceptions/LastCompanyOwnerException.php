<?php

declare(strict_types=1);

namespace App\V1\Modules\Company\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\ConflictException;

class LastCompanyOwnerException extends ConflictException
{
    /**
     * @param array<string, bool|float|int|string|null> $replace
     */
    public function __construct(string $message = '', array $replace = [])
    {
        if ($message === '') {
            $message = 'company::_backend/exceptions.last_company_owner';
        }

        parent::__construct($message, $replace);
    }
}
