<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Auth\Domain\Exceptions\AuthException;
use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use App\V1\Modules\Company\Domain\Enums\CompanyUserStatus;
use App\V1\Modules\Company\Domain\Exceptions\CompanyAccessDeniedException;
use App\V1\Modules\Company\Domain\Exceptions\CompanyNotFoundException;
use App\V1\Modules\Company\Domain\Exceptions\LastCompanyOwnerException;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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
     * @throws CompanyNotFoundException|Throwable
     */
    public function getAuthenticatedUserCompany(): Company
    {
        $company = $this->getAuthenticatedUser()->company;

        throw_if(! $company instanceof Company, CompanyNotFoundException::class);

        return $company;
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

    /**
     * @return Collection<int, User>
     */
    public function getByCompany(Company $company): Collection
    {
        return $this->userQuery()
            ->where('company_id', $company->id)
            ->orderBy('created_at')
            ->get();
    }

    /**
     * @throws CompanyAccessDeniedException|Throwable
     */
    public function findCompanyUser(Company $company, string $userId): User
    {
        $user = $this->userQuery()
            ->where('company_id', $company->id)
            ->where('id', $userId)
            ->first();

        throw_if(! $user instanceof User, CompanyAccessDeniedException::class);

        return $user;
    }

    public function activeOwnerCount(Company $company): int
    {
        return $this->userQuery()
            ->where('company_id', $company->id)
            ->where('role', CompanyUserRole::OWNER->value)
            ->where('status', CompanyUserStatus::ACTIVE->value)
            ->count();
    }

    /**
     * @throws LastCompanyOwnerException|Throwable
     */
    public function deactivateCompanyUser(User $user): User
    {
        throw_if(
            $user->hasCompanyRole(CompanyUserRole::OWNER)
            && $user->company instanceof Company
            && $this->activeOwnerCount($user->company) <= 1,
            LastCompanyOwnerException::class
        );

        $user->fill([
            'status' => CompanyUserStatus::DEACTIVATED->value,
            'deactivated_at' => now(),
        ])->save();

        return $user;
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
