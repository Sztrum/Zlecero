<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Application\Queries;

use App\V1\Modules\Company\Infrastructure\Repositories\CompanyRepository;
use App\V1\Modules\User\Domain\Aggregates\UserAggregate;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

readonly class IndexUserCompaniesQuery
{
    public function __construct(
        private CompanyRepository $companyRepository,
        private UserRepository $userRepository,
        private UserAggregate $userAggregate,
        private AuthManager $authManager,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function execute(
        string $user_id,
    ): Collection {
        $user = $this->userRepository->findById($user_id);

        // TODO: in future valide if this user is authorized to list children companies of companies retrieved here, if not dont return them, but i think this validation should be implemented in CompanyResource also
        // TODO: also if authenticated user can list companies of another user
        //        $this->userAggregate->determineIfGivenUserCanListChildrenCompanies();

        return $this->companyRepository->getCompaniesByUserId(
            userId: $user_id,
        );
    }
}
