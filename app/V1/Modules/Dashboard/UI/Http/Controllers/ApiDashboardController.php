<?php

declare(strict_types=1);

namespace App\V1\Modules\Dashboard\UI\Http\Controllers;

use App\V1\Core\Domain\Exceptions\ForbiddenException;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\Dashboard\Infrastructure\Repositories\DashboardRepository;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ApiDashboardController extends ApiController
{
    /**
     * @throws Throwable
     */
    public function company(
        Request $request,
        UserRepository $userRepository,
        DashboardRepository $dashboardRepository
    ): JsonResponse {
        $user = $userRepository->getAuthenticatedUser();
        $company = $userRepository->getAuthenticatedUserCompany();

        return $this->responseData($dashboardRepository->getCompanyDashboard(
            $company,
            $user,
            $request->query('owner') === 'me' ? 'me' : null
        ));
    }

    /**
     * @throws Throwable
     */
    public function admin(
        UserRepository $userRepository,
        DashboardRepository $dashboardRepository
    ): JsonResponse {
        $user = $userRepository->getAuthenticatedUser();

        throw_if(! $dashboardRepository->canViewAdminDashboard($user), ForbiddenException::class);

        return $this->responseData($dashboardRepository->getAdminDashboard());
    }
}
