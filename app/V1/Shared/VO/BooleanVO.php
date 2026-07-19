<?php

declare(strict_types=1);

namespace App\V1\Shared\VO;

use JsonSerializable;

class BooleanVO implements JsonSerializable
{
    public readonly bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function jsonSerialize(): bool
    {
        return $this->value;
    }
}
