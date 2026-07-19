<?php

declare(strict_types=1);

namespace App\V1\Core\Infrastructure\Repositories\Eloquent;

use App\V1\Core\Domain\Models\Model;
use App\V1\Core\Infrastructure\Repositories\ModelRepositoryInterface;
use App\V1\Modules\User\Domain\Exceptions\UserNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Throwable;

abstract class EloquentModelRepository implements ModelRepositoryInterface
{
    abstract public function model(): Model;
    abstract public function moduleName(): string;

    protected function query(): Builder
    {
        return $this->model()->query();
    }

    public function create(array $params): Model
    {
        return $this->query()->create($params);
    }

    public function update(Model $model, array $params): Model
    {
        $model->fill($params)->save();

        return $model;
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

    /**
     * @throws Throwable|UserNotFoundException
     */
    public function findById(string $id): Model
    {
        $user = $this->firstById($id);

        throw_if(!$user, UserNotFoundException::class);

        return $user;
    }

    public function firstById(string $id): ?Model
    {
        /** @var Model|null $model */
        return $this->query()->find($id);
    }

    public function all(): Collection
    {
        return $this->query()->get();
    }
}
