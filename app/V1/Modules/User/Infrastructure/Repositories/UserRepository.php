<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Auth\Domain\Exceptions\AuthException;
use App\V1\Modules\User\Domain\Models\User;
use Throwable;

class UserRepository extends EloquentModelRepository
{
    public function model(): User
    {
        return new User();
    }

    public function moduleName(): string
    {
        return 'user';
    }

    /**
     * @throws Throwable
     */
    public function getAuthenticatedUser(): User
    {
        /** @var ?User $user */
        $user = auth()->user();

        throw_if(!$user, AuthException::class);

        return $user;
    }

    public function firstByEmail(
        string $email
    ): ?User {
        /** @var ?User $user */
        return $this->query()
            ->where('email', $email)
            ->first();
    }

    public function findByIdAndRememberToken(
        string $user_id,
        string $remember_token
    ): User {
        return $this->model()
            ->query()
            ->where('id', $user_id)
            ->where('remember_token', $remember_token)
            ->firstOrFail();
    }

    public function findByEmail(
        string $email
    ): User {
        return $this->query()
            ->where('email', $email)
            ->firstOrFail();
    }

    public function findByRememberToken(
        string $remember_token
    ): User {
        return $this->model()
            ->query()
            ->where('remember_token', $remember_token)
            ->firstOrFail();
    }
}
