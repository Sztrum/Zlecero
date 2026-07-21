<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Aggregates;

use App\V1\Modules\Auth\Domain\Exceptions\AuthException;
use App\V1\Modules\User\Domain\Exceptions\ErrorWhileResetPasswordException;
use App\V1\Modules\User\Domain\Exceptions\InvalidEmailVerificationHashException;
use App\V1\Modules\User\Domain\Exceptions\UserEmailAlreadyVerifiedException;
use App\V1\Modules\User\Domain\Exceptions\UserEmailIsNotVerifiedException;
use App\V1\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\V1\Modules\User\Domain\Exceptions\UserWithEmailExistsException;
use App\V1\Modules\User\Domain\Exceptions\UserWithEmailNotExistsException;
use App\V1\Modules\User\Domain\Models\User;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Hashing\Hasher;
use Throwable;

readonly class UserAggregate
{
    public function __construct(
        private UserRepository $repository,
        private Hasher $hasher
    ) {}

    /**
     * @throws Throwable
     */
    public function checkUserWithEmailAlreadyExist(
        string $email
    ): self {
        throw_if(
            $this->repository->firstByEmail($email),
            UserWithEmailExistsException::class
        );

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function verifyEmailVerificationHash(
        User $user,
        string $hash
    ): self {
        throw_if(
            ! $user->verifyEmailVerificationHash($hash),
            InvalidEmailVerificationHashException::class
        );

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function userEmailAlreadyVerified(
        User $user
    ): self {
        try {
            $this->validateVerifiedEmail($user);
        } catch (UserEmailIsNotVerifiedException $exception) {
            return $this;
        }

        throw new UserEmailAlreadyVerifiedException;
    }

    /**
     * @throws Throwable
     */
    public function validateVerifiedEmail(
        User $user
    ): self {
        throw_if(
            ! $user->hasVerifiedEmail(),
            UserEmailIsNotVerifiedException::class
        );

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function verifyPassword(User $user, string $password): self
    {
        throw_if(
            ! $this->hasher->check($password, $user->password),
            AuthException::class
        );

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function checkUserWithEmailNotExist(
        string $email
    ): self {
        try {
            $this->checkUserWithEmailAlreadyExist($email);
        } catch (UserWithEmailExistsException $exception) {
            return $this;
        }

        throw new UserWithEmailNotExistsException;
    }

    /**
     * @throws Throwable
     */
    public function verifyResetPasswordStatus(
        string $status
    ): self {
        throw_if(
            $status !== PasswordBrokerContract::PASSWORD_RESET,
            ErrorWhileResetPasswordException::class
        );

        return $this;
    }

    /**
     * @throws UserNotFoundException|Throwable
     */
    public function ensureUserExists(
        ?User $user,
    ): void {
        throw_if(! $user, UserNotFoundException::class);
    }
}
