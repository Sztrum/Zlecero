<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AccessTokenResource extends JsonResource
{
    public static $wrap = null;

    public function __construct(private readonly NewAccessToken $accessToken)
    {
        parent::__construct($accessToken);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => Response::HTTP_OK,
            'message' => __('auth::messages.auth_success'),
            'token' => $this->accessToken->plainTextToken,
        ];
    }
}
