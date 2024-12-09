<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class ExceptionHandler extends Handler
{
    protected $dontReport = [
        RouteNotFoundException::class,
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e): Response|JsonResponse|RedirectResponse
    {
        $isDebug = config('app.debug');

        return $isDebug
            ? $this->buildJsonResponse($e, true)
            : $this->buildJsonResponse($e);
    }

    private function buildJsonResponse(Throwable $e, bool $isDebug = false): JsonResponse
    {
        $exceptionMap = $this->getExceptionMap($isDebug);

        foreach ($exceptionMap as $exceptionClass => $response) {
            if ($e instanceof $exceptionClass) {
                $responseData = is_callable($response) ? $response($e) : $response;

                if ($isDebug) {
                    $responseData['data'] = $this->getDebugData($e);
                }

                return new JsonResponse(
                    $responseData,
                    $responseData['status'] ?? Response::HTTP_INTERNAL_SERVER_ERROR,
                    [],
                    JSON_UNESCAPED_UNICODE
                );
            }
        }

        return $this->buildDefaultResponse($e, $isDebug);
    }

    private function buildDefaultResponse(Throwable $e, bool $isDebug): JsonResponse
    {
        $responseData = [
            'status' => $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $e->getMessage(),
        ];

        if ($isDebug) {
            $responseData['data'] = $this->getDebugData($e);
        }

        return new JsonResponse($responseData, $responseData['status'], [], JSON_UNESCAPED_UNICODE);
    }

    private function getDebugData(Throwable $e): array
    {
        return [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
        ];
    }

    private function getExceptionMap(bool $isDebug): array
    {
        return [
            ThrottleRequestsException::class => [
                'status' => Response::HTTP_TOO_MANY_REQUESTS,
                'message' => __('core::validation.throttle'),
            ],
            ValidationException::class => fn ($e) => [
                'status' => $e->status,
                'message' => __('core::validation.invalid_data'),
                'errors' => $e->errors(),
            ],
            ModelNotFoundException::class => [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => __('core::domain.model_not_found'),
            ],
            NotFoundHttpException::class => [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => __('core::domain.model_not_found'),
            ],
            HttpException::class => fn ($e) => [
                'status' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ],
            AuthenticationException::class => [
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => __('core::domain.unauthenticated'),
            ],
        ];
    }
}
