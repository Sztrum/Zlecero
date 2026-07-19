<?php

declare(strict_types=1);

namespace App\V1\Shared\VO;

use JsonSerializable;
use Stringable;

class StringVO implements JsonSerializable, Stringable
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = htmlentities($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->__toString();
    }
}
