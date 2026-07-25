<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\Customer\Application\Commands\CreateCustomerCommand;
use App\V1\Modules\Customer\Application\Commands\UpdateCustomerCommand;
use App\V1\Modules\Customer\Domain\Models\Customer;
use App\V1\Modules\Customer\Infrastructure\Repositories\CustomerRepository;
use App\V1\Modules\Customer\UI\Http\Requests\ApiStoreCustomerRequest;
use App\V1\Modules\Customer\UI\Http\Requests\ApiUpdateCustomerRequest;
use App\V1\Modules\Customer\UI\Http\Resources\CustomerResource;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiCustomerController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
        private readonly UserRepository $userRepository,
        private readonly CustomerRepository $customerRepository,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    /**
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $search = $request->query('search');
        $customers = $this->customerRepository
            ->getByCompany(
                $company,
                is_string($search) ? $search : null,
            )
            ->map(fn (Customer $customer) => (new CustomerResource(
                resource: $customer,
                potentialDuplicates: $this->customerRepository->getPotentialDuplicates($company, $customer),
            ))->toArray($request))
            ->values()
            ->all();

        return $this->responseData([
            'customers' => $customers,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(ApiStoreCustomerRequest $request): JsonResponse
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $customerId = (string) Uuid::uuid4();

        $this->commandBus->dispatch(new CreateCustomerCommand(
            id: $customerId,
            companyId: $company->id,
            type: $this->validatedString($request, 'type'),
            displayName: $this->validatedString($request, 'display_name'),
            companyName: $this->nullableString($request, 'company_name'),
            firstName: $this->nullableString($request, 'first_name'),
            lastName: $this->nullableString($request, 'last_name'),
            email: $this->nullableString($request, 'email'),
            phone: $this->nullableString($request, 'phone'),
            taxNumber: $this->nullableString($request, 'tax_number'),
            addressLine: $this->nullableString($request, 'address_line'),
            postalCode: $this->nullableString($request, 'postal_code'),
            city: $this->nullableString($request, 'city'),
            countryCode: $this->validatedString($request, 'country_code'),
            notes: $this->nullableString($request, 'notes'),
        ));

        $customer = $this->customerRepository->findCompanyCustomer($company, $customerId);

        return $this->responseData((new CustomerResource(
            resource: $customer,
            potentialDuplicates: $this->customerRepository->getPotentialDuplicates($company, $customer),
            includeHistory: true,
        ))->toArray($request), statusCode: Response::HTTP_CREATED);
    }

    /**
     * @throws Throwable
     */
    public function show(Request $request): CustomerResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $customer = $this->customerRepository->findCompanyCustomer(
            $company,
            $this->routeString($request, 'customer_id'),
        );

        return new CustomerResource(
            resource: $customer,
            potentialDuplicates: $this->customerRepository->getPotentialDuplicates($company, $customer),
            includeHistory: true,
            asResponse: true,
        );
    }

    /**
     * @throws Throwable
     */
    public function update(ApiUpdateCustomerRequest $request): CustomerResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $customer = $this->customerRepository->findCompanyCustomer(
            $company,
            $this->routeString($request, 'customer_id'),
        );

        $this->commandBus->dispatch(new UpdateCustomerCommand(
            customer: $customer,
            type: $this->validatedString($request, 'type'),
            displayName: $this->validatedString($request, 'display_name'),
            companyName: $this->nullableString($request, 'company_name'),
            firstName: $this->nullableString($request, 'first_name'),
            lastName: $this->nullableString($request, 'last_name'),
            email: $this->nullableString($request, 'email'),
            phone: $this->nullableString($request, 'phone'),
            taxNumber: $this->nullableString($request, 'tax_number'),
            addressLine: $this->nullableString($request, 'address_line'),
            postalCode: $this->nullableString($request, 'postal_code'),
            city: $this->nullableString($request, 'city'),
            countryCode: $this->validatedString($request, 'country_code'),
            notes: $this->nullableString($request, 'notes'),
        ));

        $customer->refresh();

        return new CustomerResource(
            resource: $customer,
            potentialDuplicates: $this->customerRepository->getPotentialDuplicates($company, $customer),
            includeHistory: true,
            asResponse: true,
        );
    }

    private function validatedString(Request $request, string $key): string
    {
        $value = $request->input($key);

        if (! is_string($value)) {
            throw new RuntimeException("Validated request field [{$key}] must be a string.");
        }

        return $value;
    }

    private function nullableString(Request $request, string $key): ?string
    {
        $value = $request->input($key);

        if ($value === null || $value === '') {
            return null;
        }

        if (! is_string($value)) {
            throw new RuntimeException("Validated request field [{$key}] must be null or string.");
        }

        return $value;
    }

    private function routeString(Request $request, string $key): string
    {
        $value = $request->route($key);

        if (! is_string($value)) {
            throw new RuntimeException("Route parameter [{$key}] must be a string.");
        }

        return $value;
    }
}
