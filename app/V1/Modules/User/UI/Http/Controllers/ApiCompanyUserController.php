<?php

declare(strict_types=1);

namespace App\V1\Modules\User\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use App\V1\Modules\Company\Domain\Enums\CompanyUserStatus;
use App\V1\Modules\Company\Domain\Exceptions\CompanyAccessDeniedException;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use App\V1\Modules\User\Domain\Models\User;
use App\V1\Modules\User\UI\Http\Requests\ApiInviteCompanyUserRequest;
use App\V1\Modules\User\UI\Http\Resources\CompanyUserResource;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Throwable;

class ApiCompanyUserController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    /**
     * @throws Throwable
     */
    public function index(): JsonResponse
    {
        $users = $this->userRepository
            ->getByCompany($this->userRepository->getAuthenticatedUserCompany())
            ->map(static fn ($user) => (new CompanyUserResource($user))->toArray(request()))
            ->values()
            ->all();

        return $this->responseData([
            'users' => $users,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(ApiInviteCompanyUserRequest $request): CompanyUserResource
    {
        $this->ensureCanManageUsers();

        $user = $this->userRepository->create([
            'company_id' => $this->userRepository->getAuthenticatedUserCompany()->id,
            'name' => $this->validatedString($request, 'name'),
            'email' => $this->validatedString($request, 'email'),
            'password' => Hash::make(Str::random(36)),
            'role' => $this->validatedString($request, 'role'),
            'status' => CompanyUserStatus::INVITED->value,
            'invited_at' => now(),
            'remember_token' => Uuid::uuid4(),
        ]);

        if (! $user instanceof User) {
            throw new CompanyAccessDeniedException();
        }

        return new CompanyUserResource(
            resource: $user,
            asResponse: true,
        );
    }

    /**
     * @throws Throwable
     */
    public function deactivate(Request $request): CompanyUserResource
    {
        $this->ensureCanManageUsers();

        $userId = $request->route('user_id');

        if (! is_string($userId)) {
            throw new CompanyAccessDeniedException();
        }

        $user = $this->userRepository->deactivateCompanyUser(
            $this->userRepository->findCompanyUser(
                $this->userRepository->getAuthenticatedUserCompany(),
                $userId,
            ),
        );

        return new CompanyUserResource(
            resource: $user,
            asResponse: true,
        );
    }

    /**
     * @throws CompanyAccessDeniedException|Throwable
     */
    private function ensureCanManageUsers(): void
    {
        throw_if(! $this->userRepository->getAuthenticatedUser()->hasAnyCompanyRole([
            CompanyUserRole::OWNER,
            CompanyUserRole::ADMIN,
        ]), CompanyAccessDeniedException::class);
    }

    private function validatedString(ApiInviteCompanyUserRequest $request, string $key): string
    {
        $value = $request->input($key);

        if (! is_string($value)) {
            throw new CompanyAccessDeniedException();
        }

        return $value;
    }
}
