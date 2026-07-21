<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;

abstract class Model extends BaseModel
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function (BaseModel $model): void {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
}
