<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use App\V1\Core\Infrastructure\Packages\Sanctum\Models\PersonalAccessToken;
use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\Auth\Application\Commands\ForgotPasswordCommand;
use App\V1\Modules\Auth\Application\Commands\ResetPasswordCommand;
use App\V1\Modules\Auth\Application\Commands\SetNewPasswordCommand;
use App\V1\Modules\Auth\Application\Commands\SetUserRememberTokenCommand;
use App\V1\Modules\Auth\Application\Commands\VerifyUserEmailCommand;
use App\V1\Modules\Auth\Domain\DTO\LoginDataDTO;
use App\V1\Modules\Auth\Domain\Services\AuthService;
use App\V1\Modules\Auth\UI\Http\Requests\ApiForgotPasswordRequest;
use App\V1\Modules\Auth\UI\Http\Requests\ApiLoginRequest;
use App\V1\Modules\Auth\UI\Http\Requests\ApiRegisterUserRequest;
use App\V1\Modules\Auth\UI\Http\Requests\ApiResetPasswordRequest;
use App\V1\Modules\Auth\UI\Http\Requests\ApiSetNewPasswordRequest;
use App\V1\Modules\Auth\UI\Http\Requests\ApiVerifyEmailRequest;
use App\V1\Modules\User\Application\Commands\RegisterUserCommand;
use App\V1\Modules\User\UI\Http\Resources\UserResource;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        private readonly ResponseFactory $responseFactory,
        private readonly AuthService $authService,
    ) {
        parent::__construct($commandBus, $responseFactory);
    }

    public function login(ApiLoginRequest $request): JsonResponse
    {
        $token = $this->authService->login(
            loginDataDTO: LoginDataDTO::from($request)
        );

        return $this->responseData([
            'token' => $token->plainTextToken,
        ], __('auth::messages.auth_success'));
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $accessToken = $user?->currentAccessToken();

        if ($accessToken instanceof PersonalAccessToken) {
            $accessToken->delete();
        }

        return $this->responseFactory->json([
            'status' => Response::HTTP_OK,
            'message' => __('auth::messages.logout'),
        ]);
    }

    public function register(ApiRegisterUserRequest $request): JsonResponse
    {
        $this->commandBus->dispatch(
            new RegisterUserCommand(
                name: $this->validatedString($request, 'name'),
                email: $this->validatedString($request, 'email'),
            )
        );

        return $this->responseFactory->json([
            'status' => Response::HTTP_OK,
            'message' => __('auth::messages.user_registered'),
        ]);
    }

    public function verifyEmail(
        ApiVerifyEmailRequest $request,
        string $user_id,
        string $hash,
    ): JsonResponse {
        $rememberToken = Uuid::uuid4()->toString();

        $this->commandBus->dispatchManyWithTransaction(
            new VerifyUserEmailCommand($user_id, $hash),
            new SetUserRememberTokenCommand($user_id, $rememberToken)
        );

        return $this->responseFactory->json([
            'status' => Response::HTTP_OK,
            'message' => __('auth::messages.email_verified'),
            'data' => [
                'user_id' => $user_id,
                'remember_token' => $rememberToken,
            ],
        ]);
    }

    public function newPassword(
        ApiSetNewPasswordRequest $request,
        string $user_id,
        string $remember_token,
    ): JsonResponse {
        $this->commandBus->dispatch(
            new SetNewPasswordCommand(
                user_id: $user_id,
                remember_token: $remember_token,
                password: $this->validatedString($request, 'password')
            ),
        );

        return $this->responseFactory->json([
            'status' => Response::HTTP_OK,
            'message' => __('auth::messages.password_changed'),
        ]);
    }

    public function forgotPassword(ApiForgotPasswordRequest $request): JsonResponse
    {
        try {
            $this->commandBus->dispatch(
                new ForgotPasswordCommand(
                    $this->validatedString($request, 'email')
                )
            );
        } catch (Throwable $e) {
        }

        return $this->responseFactory->json([
            'status' => Response::HTTP_OK,
            'message' => __('auth::messages.forgot_password'),
        ]);
    }

    public function resetPassword(
        ApiResetPasswordRequest $request,
        string $remember_token,
    ): JsonResponse {
        $this->commandBus->dispatch(
            new ResetPasswordCommand(
                remember_token: $remember_token,
                token: $this->validatedString($request, 'token'),
                password: $this->validatedString($request, 'password'),
            )
        );

        return $this->responseFactory->json([
            'status' => Response::HTTP_OK,
            'message' => __('auth::messages.reset_password'),
        ]);
    }

    public function profile(Request $request): UserResource
    {
        return new UserResource(
            resource: $request->user(),
            asResponse: true,
        );
    }

    private function validatedString(Request $request, string $key): string
    {
        $value = $request->input($key);

        if (!is_string($value)) {
            throw new RuntimeException("Validated request field [{$key}] must be a string.");
        }

        return $value;
    }
}
