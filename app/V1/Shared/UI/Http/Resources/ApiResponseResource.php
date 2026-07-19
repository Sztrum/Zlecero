<?php

declare(strict_types=1);

namespace App\V1\Shared\UI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiResponseResource extends JsonResource
{
    public function __construct(
        $resource,
        protected ?string $relationships = null,
        protected bool $asResponse = false,
        protected array $additionalData = [],
    ) {
        parent::__construct($resource);
    }

    abstract public function getResourceData(): array;

    abstract public function getRelationships(): array;

    public function toArray($request): array
    {
        if ($this->asResponse) {
            return [
                'status' => Response::HTTP_OK,
                'data' => $this->getAllResourceData() + $this->additionalData
            ];
        }

        return $this->getAllResourceData();
    }

    private function getAllResourceData(): array
    {
        return (isset($this->resource->id) ? [
            'id' => $this->resource->id,
        ] : []) + $this->getResourceData() + $this->getRelationships();
    }

    protected function getRelationshipsNames(): Collection
    {
        if ($this->relationships === '' || $this->relationships === null) {
            return collect();
        }

        return collect(explode(',', trim($this->relationships)));
    }
}
