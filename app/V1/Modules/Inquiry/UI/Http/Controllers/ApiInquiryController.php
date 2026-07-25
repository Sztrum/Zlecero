<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Customer\Infrastructure\Repositories\CustomerRepository;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryMessageDirection;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryStatus;
use App\V1\Modules\Inquiry\Domain\Models\Inquiry;
use App\V1\Modules\Inquiry\Infrastructure\Repositories\InquiryRepository;
use App\V1\Modules\Inquiry\UI\Http\Requests\ApiChangeInquiryStatusRequest;
use App\V1\Modules\Inquiry\UI\Http\Requests\ApiStoreInquiryMessageRequest;
use App\V1\Modules\Inquiry\UI\Http\Requests\ApiStoreInquiryRequest;
use App\V1\Modules\Inquiry\UI\Http\Requests\ApiUpdateInquiryRequest;
use App\V1\Modules\Inquiry\UI\Http\Resources\InquiryResource;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiInquiryController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
        private readonly UserRepository $userRepository,
        private readonly CustomerRepository $customerRepository,
        private readonly InquiryRepository $inquiryRepository,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    /**
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'status' => $this->nullableQueryString($request, 'status'),
            'priority' => $this->nullableQueryString($request, 'priority'),
            'queue' => $this->nullableQueryString($request, 'queue'),
            'archived' => $this->nullableQueryString($request, 'archived'),
            'owner' => $this->nullableQueryString($request, 'owner'),
            'owner_user_id' => $this->userRepository->getAuthenticatedUser()->id,
        ];

        $inquiries = $this->inquiryRepository
            ->getByCompany($this->userRepository->getAuthenticatedUserCompany(), $filters)
            ->map(static fn (Inquiry $inquiry) => (new InquiryResource($inquiry))->toArray($request))
            ->values()
            ->all();

        return $this->responseData([
            'inquiries' => $inquiries,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(ApiStoreInquiryRequest $request): JsonResponse
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $inquiry = $this->inquiryRepository->createInquiry(
            $this->payload($request, $company) + [
                'source' => 'manual',
                'status' => InquiryStatus::NEW->value,
            ],
            $this->userRepository->getAuthenticatedUser(),
        );

        $inquiry = $this->inquiryRepository->findCompanyInquiry($company, $inquiry->id);

        return $this->responseData(
            (new InquiryResource($inquiry))->toArray($request),
            statusCode: Response::HTTP_CREATED,
        );
    }

    /**
     * @throws Throwable
     */
    public function show(Request $request): InquiryResource
    {
        return new InquiryResource(
            resource: $this->inquiryRepository->findCompanyInquiry(
                $this->userRepository->getAuthenticatedUserCompany(),
                $this->routeString($request, 'inquiry_id'),
            ),
            asResponse: true,
        );
    }

    /**
     * @throws Throwable
     */
    public function update(ApiUpdateInquiryRequest $request): InquiryResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $inquiry = $this->inquiryRepository->findCompanyInquiry($company, $this->routeString($request, 'inquiry_id'));

        $this->inquiryRepository->updateInquiry($inquiry, $this->payload($request, $company));
        $inquiry = $this->inquiryRepository->findCompanyInquiry($company, $inquiry->id);

        return new InquiryResource(resource: $inquiry, asResponse: true);
    }

    /**
     * @throws Throwable
     */
    public function changeStatus(ApiChangeInquiryStatusRequest $request): InquiryResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $inquiry = $this->inquiryRepository->findCompanyInquiry($company, $this->routeString($request, 'inquiry_id'));
        $this->inquiryRepository->changeStatus(
            $inquiry,
            InquiryStatus::from($this->validatedString($request, 'status')),
            $this->userRepository->getAuthenticatedUser(),
        );

        return new InquiryResource(
            resource: $this->inquiryRepository->findCompanyInquiry($company, $inquiry->id),
            asResponse: true,
        );
    }

    /**
     * @throws Throwable
     */
    public function archive(Request $request): InquiryResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $inquiry = $this->inquiryRepository->archive(
            $this->inquiryRepository->findCompanyInquiry($company, $this->routeString($request, 'inquiry_id')),
        );

        return new InquiryResource(resource: $inquiry, asResponse: true);
    }

    /**
     * @throws Throwable
     */
    public function restore(Request $request): InquiryResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $inquiry = $this->inquiryRepository->restore(
            $this->inquiryRepository->findCompanyInquiry($company, $this->routeString($request, 'inquiry_id')),
        );

        return new InquiryResource(resource: $inquiry, asResponse: true);
    }

    /**
     * @throws Throwable
     */
    public function storeMessage(ApiStoreInquiryMessageRequest $request): InquiryResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $inquiry = $this->inquiryRepository->findCompanyInquiry($company, $this->routeString($request, 'inquiry_id'));

        $this->inquiryRepository->createMessage($inquiry, [
            'created_by_user_id' => $this->userRepository->getAuthenticatedUser()->id,
            'direction' => $this->validatedString($request, 'direction'),
            'sender_name' => $this->nullableString($request, 'sender_name'),
            'sender_email' => $this->nullableString($request, 'sender_email'),
            'recipient_email' => $this->nullableString($request, 'recipient_email'),
            'subject' => $this->nullableString($request, 'subject'),
            'body' => $this->validatedString($request, 'body'),
            'external_message_id' => $this->nullableString($request, 'external_message_id'),
            'external_thread_id' => $this->nullableString($request, 'external_thread_id'),
            'sent_at' => $this->nullableString($request, 'sent_at') ?? now(),
        ]);

        if ($this->validatedString($request, 'direction') === InquiryMessageDirection::OUTBOUND->value) {
            $this->inquiryRepository->changeStatus(
                $inquiry,
                InquiryStatus::WAITING_FOR_CUSTOMER,
                $this->userRepository->getAuthenticatedUser(),
            );
        }

        return new InquiryResource(
            resource: $this->inquiryRepository->findCompanyInquiry($company, $inquiry->id),
            asResponse: true,
        );
    }

    /**
     * @return array<string, mixed>
     * @throws Throwable
     */
    private function payload(Request $request, Company $company): array
    {
        $customerId = $this->nullableString($request, 'customer_id');
        $ownerUserId = $this->nullableString($request, 'owner_user_id');

        if ($customerId !== null) {
            $this->customerRepository->findCompanyCustomer($company, $customerId);
        }

        if ($ownerUserId !== null) {
            $this->userRepository->findCompanyUser($company, $ownerUserId);
        }

        return [
            'company_id' => $company->id,
            'customer_id' => $customerId,
            'owner_user_id' => $ownerUserId,
            'title' => $this->validatedString($request, 'title'),
            'description' => $this->nullableString($request, 'description'),
            'priority' => $this->validatedString($request, 'priority'),
            'response_due_at' => $this->nullableString($request, 'response_due_at'),
            'realization_due_at' => $this->nullableString($request, 'realization_due_at'),
            'pickup_due_at' => $this->nullableString($request, 'pickup_due_at'),
        ];
    }

    private function nullableQueryString(Request $request, string $key): ?string
    {
        $value = $request->query($key);

        return is_string($value) && $value !== '' ? $value : null;
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
