<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Auth\Domain\Exceptions\AuthException;
use App\V1\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
        $user = auth()->user();

        throw_if(!$user instanceof User, AuthException::class);

        return $user;
    }

    /**
     * @return Builder<User>
     */
    private function userQuery(): Builder
    {
        return User::query();
    }

    public function firstByEmail(string $email): ?User
    {
        return $this->userQuery()
            ->where('email', $email)
            ->first();
    }

    public function findByIdAndRememberToken(
        string $user_id,
        string $remember_token
    ): User {
        return $this->userQuery()
            ->where('id', $user_id)
            ->where('remember_token', $remember_token)
            ->firstOrFail();
    }

    /**
     * @throws Throwable
     */
    public function findByEmail(string $email): User
    {
        $user = $this->firstByEmail($email);

        throw_if(!$user instanceof User, UserNotFoundException::class);

        return $user;
    }

    public function findByRememberToken(string $remember_token): User
    {
        return $this->userQuery()
            ->where('remember_token', $remember_token)
            ->firstOrFail();
    }
}
