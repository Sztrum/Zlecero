<?php

declare(strict_types=1);

namespace App\V1\Modules\Company\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use App\V1\Modules\Company\Domain\Exceptions\CompanyAccessDeniedException;
use App\V1\Modules\Company\Infrastructure\Repositories\CompanyRepository;
use App\V1\Modules\Company\UI\Http\Requests\ApiUpdateCompanyRequest;
use App\V1\Modules\Company\UI\Http\Resources\CompanyResource;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use RuntimeException;
use Throwable;

class ApiCompanyController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
        private readonly UserRepository $userRepository,
        private readonly CompanyRepository $companyRepository,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    /**
     * @throws Throwable
     */
    public function show(): CompanyResource
    {
        return new CompanyResource(
            resource: $this->userRepository->getAuthenticatedUserCompany(),
            asResponse: true,
        );
    }

    /**
     * @throws Throwable
     */
    public function update(ApiUpdateCompanyRequest $request): CompanyResource
    {
        $user = $this->userRepository->getAuthenticatedUser();

        throw_if(! $user->hasAnyCompanyRole([
            CompanyUserRole::OWNER,
            CompanyUserRole::ADMIN,
        ]), CompanyAccessDeniedException::class);

        $company = $this->companyRepository->update(
            $this->userRepository->getAuthenticatedUserCompany(),
            [
                'name' => $this->validatedString($request, 'name'),
                'billing_name' => $request->input('billing_name'),
                'tax_number' => $request->input('tax_number'),
                'contact_email' => $request->input('contact_email'),
                'contact_phone' => $request->input('contact_phone'),
                'address_line' => $request->input('address_line'),
                'postal_code' => $request->input('postal_code'),
                'city' => $request->input('city'),
                'country_code' => $this->validatedString($request, 'country_code'),
                'brand_color' => $this->validatedString($request, 'brand_color'),
            ],
        );

        return new CompanyResource(
            resource: $company,
            asResponse: true,
        );
    }

    private function validatedString(ApiUpdateCompanyRequest $request, string $key): string
    {
        $value = $request->input($key);

        if (! is_string($value)) {
            throw new RuntimeException("Validated request field [{$key}] must be a string.");
        }

        return $value;
    }
}
