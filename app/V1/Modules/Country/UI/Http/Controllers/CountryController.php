<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\Country\Domain\Services\CountryService;
use App\V1\Modules\Country\UI\Http\Resources\CollectionCountryEntityResource;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class CountryController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        ResponseFactory $responseFactory,
        private CountryService $countryService,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    public function index(Request $request): CollectionCountryEntityResource
    {
        return new CollectionCountryEntityResource(
            resource: $this->countryService->getAllCountries()->toCollection()->values(),
            asResponse: true,
        );
    }
}
