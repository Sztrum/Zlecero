<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Application\Queries;

use App\V1\Modules\Company\Domain\Exceptions\CompanyNotFoundException;
use App\V1\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\V1\Modules\User\Domain\Models\User;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

readonly class IndexUserReceivedCompanyInvitationsQuery
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @throws Throwable|CompanyNotFoundException
     */
    public function execute(
        string $user_id,
    ): Collection {
        /** @var User|null $user */
        $user = $this->userRepository->firstById($user_id);

        throw_if(!$user, UserNotFoundException::class);

        // TODO: in future when permissions system will be implemented, check if authenticated user has permission to view this user's received company invitations
        // TODO: or authenticated user is the same as the user whose received company invitations are being viewed

        return $user->receivedCompanyInvitations;
    }
}
