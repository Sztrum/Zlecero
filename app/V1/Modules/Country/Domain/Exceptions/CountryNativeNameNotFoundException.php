<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class CountryNativeNameNotFoundException extends DomainException
{
    /**
     * @param  array<string, bool|float|int|string|null>  $replace
     */
    public function __construct(
        string $message = '',
        array $replace = []
    ) {
        if ($message === '') {
            $message = 'countries::_backend/exceptions.country_native_name_not_found';
        }

        parent::__construct($message, $replace);
    }
}
