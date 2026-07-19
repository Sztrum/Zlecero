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
use Exception;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Hashing\Hasher;
use Throwable;

readonly class UserAggregate
{
    public function __construct(
        private UserRepository $repository,
        private Hasher         $hasher
    ) {
    }

    /**
     * @throws Throwable
     */
    public function checkUserWithEmailAlreadyExist(
        string $email
    ): self {
        throw_if(
            $this->repository->firstByEmail($email),
            UserWithEmailExistsException::class,
            __('user::domain.user_with_email_already_exist')
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
            !$user->verifyEmailVerificationHash($hash),
            InvalidEmailVerificationHashException::class,
            __('user::domain.invalid_email_verification_hash')
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
        } catch (Exception $exception) {
            return $this;
        }

        throw new UserEmailAlreadyVerifiedException(
            __('user::domain.user_email_already_confirmed')
        );
    }

    /**
     * @throws Throwable
     */
    public function validateVerifiedEmail(
        User $user
    ): self {
        throw_if(
            !$user->hasVerifiedEmail(),
            UserEmailIsNotVerifiedException::class,
            __('user::domain.user_email_is_not_verified')
        );

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function verifyPassword(User $user, string $password): self
    {
        throw_if(
            !$this->hasher->check($password, $user->password),
            new AuthException(__('auth::messages.auth_failed'))
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
        } catch (Exception $exception) {
            return $this;
        }

        throw new UserWithEmailNotExistsException(
            __('user::domain.user_with_email_not_exist')
        );
    }

    /**
     * @throws Throwable
     */
    public function verifyResetPasswordStatus(
        string $status
    ): self {
        throw_if(
            $status !== PasswordBrokerContract::PASSWORD_RESET,
            ErrorWhileResetPasswordException::class,
            __("auth::{$status}")
        );

        return $this;
    }

    /**
     * @throws UserNotFoundException|Throwable
     */
    public function ensureUserExists(
        ?User $user,
    ): void {
        throw_if(!$user, new UserNotFoundException());
    }
}
