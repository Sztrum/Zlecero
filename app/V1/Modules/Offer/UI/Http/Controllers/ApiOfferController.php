<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\Inquiry\Infrastructure\Repositories\InquiryRepository;
use App\V1\Modules\Offer\Domain\Exceptions\InvalidOfferStateException;
use App\V1\Modules\Offer\Infrastructure\Repositories\OfferRepository;
use App\V1\Modules\Offer\UI\Http\Requests\ApiStoreOfferRequest;
use App\V1\Modules\Offer\UI\Http\Requests\ApiUpdateOfferRequest;
use App\V1\Modules\Offer\UI\Http\Resources\OfferResource;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ApiOfferController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
        private readonly UserRepository $userRepository,
        private readonly InquiryRepository $inquiryRepository,
        private readonly OfferRepository $offerRepository,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    /**
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        $offers = $this->offerRepository
            ->getByCompany($this->userRepository->getAuthenticatedUserCompany())
            ->map(static fn ($offer) => (new OfferResource($offer))->toArray($request))
            ->values()
            ->all();

        return $this->responseData(['offers' => $offers]);
    }

    /**
     * @throws Throwable
     */
    public function store(ApiStoreOfferRequest $request): JsonResponse
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $inquiry = $this->inquiryRepository->findCompanyInquiry($company, $this->validatedString($request, 'inquiry_id'));
        $offer = $this->offerRepository->createForInquiry(
            $company,
            $inquiry,
            $this->userRepository->getAuthenticatedUser(),
            $this->payload($request),
        );

        return $this->responseData(
            (new OfferResource($offer))->toArray($request),
            statusCode: Response::HTTP_CREATED,
        );
    }

    /**
     * @throws Throwable
     */
    public function show(Request $request): OfferResource
    {
        return new OfferResource(
            resource: $this->offerRepository->findCompanyOffer(
                $this->userRepository->getAuthenticatedUserCompany(),
                $this->routeString($request, 'offer_id'),
            ),
            asResponse: true,
        );
    }

    /**
     * @throws Throwable
     */
    public function update(ApiUpdateOfferRequest $request): OfferResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $offer = $this->offerRepository->findCompanyOffer($company, $this->routeString($request, 'offer_id'));

        return new OfferResource(
            resource: $this->offerRepository->updateOffer($company, $offer, $this->payload($request)),
            asResponse: true,
        );
    }

    /**
     * @throws Throwable
     */
    public function send(Request $request): OfferResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $offer = $this->offerRepository->findCompanyOffer($company, $this->routeString($request, 'offer_id'));

        return new OfferResource(
            resource: $this->offerRepository->send($company, $offer, $this->userRepository->getAuthenticatedUser()),
            asResponse: true,
        );
    }

    /**
     * @throws Throwable
     */
    public function generatePdf(Request $request): OfferResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $offer = $this->offerRepository->findCompanyOffer($company, $this->routeString($request, 'offer_id'));

        return new OfferResource(
            resource: $this->offerRepository->generatePdf($company, $offer),
            asResponse: true,
        );
    }

    /**
     * @throws Throwable
     */
    public function downloadPdf(Request $request): StreamedResponse
    {
        $offer = $this->offerRepository->findCompanyOffer(
            $this->userRepository->getAuthenticatedUserCompany(),
            $this->routeString($request, 'offer_id'),
        );

        if ($offer->pdf_disk === null || $offer->pdf_path === null || $offer->pdf_original_name === null) {
            throw new InvalidOfferStateException();
        }

        return Storage::disk($offer->pdf_disk)->download($offer->pdf_path, $offer->pdf_original_name);
    }

    /**
     * @throws Throwable
     */
    public function accept(Request $request): OfferResource
    {
        $company = $this->userRepository->getAuthenticatedUserCompany();
        $offer = $this->offerRepository->findCompanyOffer($company, $this->routeString($request, 'offer_id'));

        return new OfferResource(
            resource: $this->offerRepository->accept($company, $offer, $this->userRepository->getAuthenticatedUser()),
            asResponse: true,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(ApiStoreOfferRequest $request): array
    {
        return $request->validated();
    }

    private function validatedString(Request $request, string $key): string
    {
        $value = $request->input($key);

        if (! is_string($value)) {
            throw new RuntimeException("Validated request field [{$key}] must be a string.");
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
