<?php

declare(strict_types=1);

namespace App\V1\Shared\Domain\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderColumnScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->orderBy('order');
    }
}
