<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Exceptions;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class ExceptionHandler extends Handler
{
    /**
     * @var list<class-string<Throwable>>
     */
    protected $dontReport = [
        RouteNotFoundException::class,
    ];

    /**
     * @var list<string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e): Response|JsonResponse|RedirectResponse
    {
        return $this->buildJsonResponse($e, config('app.debug') === true);
    }

    private function buildJsonResponse(Throwable $e, bool $isDebug = false): JsonResponse
    {
        foreach ($this->getExceptionMap() as $exceptionClass => $response) {
            if ($e instanceof $exceptionClass) {
                $responseData = $response instanceof Closure ? $response($e) : $response;

                if ($isDebug) {
                    $responseData['data'] = $this->getDebugData($e);
                }

                return new JsonResponse(
                    $responseData,
                    $responseData['status'],
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

    /**
     * @return array{exception: class-string<Throwable>, file: string, line: int, trace: array<int, mixed>}
     */
    private function getDebugData(Throwable $e): array
    {
        return [
            'exception' => $e::class,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
        ];
    }

    /**
     * @return array<class-string<Throwable>, array{status: int, message: mixed}|Closure(Throwable): array{status: int, message: mixed, errors?: mixed}>
     */
    private function getExceptionMap(): array
    {
        return [
            ThrottleRequestsException::class => [
                'status' => Response::HTTP_TOO_MANY_REQUESTS,
                'message' => __('core::validation.throttle'),
            ],
            ValidationException::class => function (Throwable $e): array {
                if (! $e instanceof ValidationException) {
                    return $this->buildThrowableResponse($e);
                }

                return [
                    'status' => $e->status,
                    'message' => __('core::validation.invalid_data'),
                    'errors' => $e->errors(),
                ];
            },
            ModelNotFoundException::class => [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => __('core::domain.model_not_found'),
            ],
            NotFoundHttpException::class => [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => __('core::domain.model_not_found'),
            ],
            HttpException::class => function (Throwable $e): array {
                if (! $e instanceof HttpException) {
                    return $this->buildThrowableResponse($e);
                }

                return [
                    'status' => $e->getStatusCode(),
                    'message' => $e->getMessage(),
                ];
            },
            AuthenticationException::class => [
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => __('core::domain.unauthenticated'),
            ],
        ];
    }

    /**
     * @return array{status: int, message: string}
     */
    private function buildThrowableResponse(Throwable $e): array
    {
        return [
            'status' => $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $e->getMessage(),
        ];
    }
}
