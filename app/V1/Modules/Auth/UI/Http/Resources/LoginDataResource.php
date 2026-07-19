<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Resources;

use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\HttpFoundation\Response;

class LoginDataResource extends JsonResource
{
    public static $wrap = false;

    public function __construct(User $user, NewAccessToken $token)
    {
        parent::__construct($token);
    }

    public function toArray($request): array
    {
        return [
            'status' => Response::HTTP_OK,
            'message' => __('auth::messages.auth_success'),
            'data' => [
                'token' => $this->resource->plainTextToken,
            ]
        ];
    }
}
