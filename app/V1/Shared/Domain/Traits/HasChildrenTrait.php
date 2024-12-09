<?php

declare(strict_types=1);

namespace App\V1\Shared\Domain\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasChildrenTrait
{
    public function children(
        array $withoutGlobalScopes = [],
        array $eagerLoadRelations = [],
        string $orderBy = null
    ): HasMany {
        if (!$orderBy && in_array('order', $this->getFillable())) {
            $orderBy = 'order';
        }

        return $this->hasMany(self::class, 'parent_id')
            ->withoutGlobalScopes($withoutGlobalScopes)
            ->with($eagerLoadRelations)
            ->when(
                $orderBy,
                fn ($query) => $query->orderBy($orderBy),
            );
    }

    public function parent(
        array $withoutGlobalScopes = [],
        array $eagerLoadRelations = [],
    ): BelongsTo {
        return $this->belongsTo(self::class, 'parent_id')
            ->withoutGlobalScopes($withoutGlobalScopes)
            ->with($eagerLoadRelations);
    }
}
