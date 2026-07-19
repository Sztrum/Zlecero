<?php

declare(strict_types=1);

namespace App\V1\Shared\UI\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiCollectionResource extends ResourceCollection
{
    public function __construct(
        $resource,
        protected ?string $relationships = null,
        protected bool $asResponse = false,
    ) {
        parent::__construct($resource);
    }

    abstract public function getResource($model): ApiResponseResource;

    public function toArray($request)
    {
        if ($this->resource instanceof LengthAwarePaginator) {
            $collection = $this->resource->getCollection();
            $mapped = $collection->map(function ($model) use ($request) {
                return $this->getResource($model)->toArray($request);
            });

            $this->resource->setCollection($mapped);

            return $this->resource;
        }

        if ($this->resource instanceof Collection) {
            return $this->resource->map(function ($model) use ($request) {
                return $this->getResource($model)->toArray($request);
            })->toArray();
        }

        return parent::toArray($request);
    }

    public function with(Request $request): array
    {
        if ($this->asResponse) {
            return [
                'status' => Response::HTTP_OK,
            ];
        }

        return [];
    }
}
