<?php

declare(strict_types=1);

namespace App\V1\Shared\UI\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use JsonSerializable;
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

    abstract public function getResource(mixed $model): ApiResponseResource;

    /**
     * @return array<array-key, mixed>|Arrayable<array-key, mixed>|JsonSerializable
     */
    public function toArray(Request $request): array|Arrayable|JsonSerializable
    {
        if ($this->resource instanceof LengthAwarePaginator) {
            $this->resource->setCollection($this->mappedCollection($this->resource->getCollection(), $request));

            return $this->resource;
        }

        if ($this->resource instanceof Collection) {
            return $this->mappedCollection($this->resource, $request)->toArray();
        }

        return parent::toArray($request);
    }

    /**
     * @param Collection<array-key, mixed> $collection
     * @return Collection<array-key, mixed>
     */
    private function mappedCollection(Collection $collection, Request $request): Collection
    {
        $items = [];

        foreach ($collection as $key => $model) {
            $items[$key] = $this->asMixed($this->getResource($model)->toArray($request));
        }

        return new Collection($items);
    }

    /**
     * @param array<string, mixed> $resource
     */
    private function asMixed(array $resource): mixed
    {
        return $resource;
    }

    /**
     * @return array<string, int>
     */
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
