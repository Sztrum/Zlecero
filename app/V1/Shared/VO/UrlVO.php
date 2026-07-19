<?php

declare(strict_types=1);

namespace App\V1\Shared\VO;

use App\V1\Core\Domain\Exceptions\DomainException;
use Throwable;

final class UrlVO extends StringVO
{
    /**
     * @throws Throwable
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
        $this->validate();
    }

    /**
     * @throws Throwable
     */
    private function validate(): void
    {
        throw_if(
            !(filter_var($this->value, FILTER_VALIDATE_URL) === $this->value)
            || !(filter_var($this->value, FILTER_SANITIZE_URL) === $this->value),
            new DomainException()
        );
    }
}
