<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property NewAccessToken $resource
 */
class AccessTokenResource extends JsonResource
{
    public static $wrap = false;

    public function __construct(NewAccessToken $resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        return [
            'status' => Response::HTTP_OK,
            'message' => __('auth::messages.auth_success'),
            'token' => $this->resource->plainTextToken,
        ];
    }
}
