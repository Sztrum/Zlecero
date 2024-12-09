<?php

declare(strict_types=1);

namespace App\V1\Shared\Domain\Traits;

use App\V1\Shared\Domain\Scopes\OrderColumnScope;

trait HasOrderTrait
{
    protected static function booted(): void
    {
        static::addGlobalScope(new OrderColumnScope());
    }
}
