<?php

declare(strict_types=1);

namespace App\V1\Core\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @throws Throwable
     */
    public function __construct(
        protected CommandBusInterface $commandBus,
        private readonly ResponseFactory $responseFactory,
    ) {
    }

    protected function responseMessage(
        string $message,
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        return $this->responseFactory->json([
            'status' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function responseData(
        array $data,
        ?string $message = null,
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        $array = ['status' => $statusCode];

        if ($message) {
            $array['message'] = $message;
        }

        $array['data'] = $data;

        return $this->responseFactory->json($array, $statusCode);
    }
}
