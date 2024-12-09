<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Domain\Services;

use App\V1\Modules\Auth\Domain\DTO\LoginDataDTO;
use App\V1\Modules\Auth\Domain\Exceptions\AuthException;
use App\V1\Modules\User\Domain\Aggregates\UserAggregate;
use App\V1\Modules\User\Domain\Models\User;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Laravel\Sanctum\NewAccessToken;
use Ramsey\Uuid\Uuid;
use Throwable;

readonly class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserAggregate  $userAggregate
    ) {
    }

    /**
     * @throws Throwable
     */
    public function login(
        LoginDataDTO $loginDataDTO,
    ): NewAccessToken {
        $user = $this->userRepository->firstByEmail($loginDataDTO->email);

        throw_if(!$user, AuthException::class);

        $this->userAggregate
            ->validateVerifiedEmail($user)
            ->verifyPassword($user, $loginDataDTO->password);

        return $this->createToken($user);
    }

    private function createToken(
        User $user
    ): NewAccessToken {
        return $user->createToken(
            Uuid::uuid4()->toString(),
        );
    }
}
