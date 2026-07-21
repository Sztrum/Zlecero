<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Exceptions;

class ReadModelNotSupportMethodException extends DomainException
{
    /**
     * @param  array<string, bool|float|int|string|null>  $replace
     */
    public function __construct(
        string $message = '',
        array $replace = []
    ) {
        if ($message === '') {
            $message = 'core::_backend/exceptions.read_model_not_support_method';
        }

        parent::__construct($message, $replace);
    }
}
