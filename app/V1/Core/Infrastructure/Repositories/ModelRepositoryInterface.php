<?php

declare(strict_types=1);

namespace App\V1\Core\Infrastructure\Repositories;

use App\V1\Core\Domain\Models\Model;

interface ModelRepositoryInterface
{
    public function model(): Model;

    /**
     * @param array<string, mixed> $params
     */
    public function create(array $params): Model;

    /**
     * @param array<string, mixed> $params
     */
    public function update(Model $model, array $params): Model;

    public function findById(string $id): Model;
}
