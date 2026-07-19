<?php

declare(strict_types=1);

namespace App\V1\Core\Infrastructure\Repositories;

use App\V1\Core\Domain\Models\Model;

interface ModelRepositoryInterface
{
    public function model(): mixed;

    public function create(array $params): mixed;

    public function update(Model $model, array $params): mixed;

    public function findById(string $id): mixed;
}
