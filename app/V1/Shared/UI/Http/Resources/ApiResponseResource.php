<?php

declare(strict_types=1);

namespace App\V1\Shared\UI\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiResponseResource extends JsonResource
{
    /**
     * @param array<string, mixed> $additionalData
     */
    public function __construct(
        $resource,
        protected ?string $relationships = null,
        protected bool $asResponse = false,
        protected array $additionalData = [],
    ) {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function getResourceData(): array;

    /**
     * @return array<string, mixed>
     */
    abstract public function getRelationships(): array;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->asResponse) {
            return [
                'status' => Response::HTTP_OK,
                'data' => $this->getAllResourceData() + $this->additionalData,
            ];
        }

        return $this->getAllResourceData();
    }

    /**
     * @return array<string, mixed>
     */
    private function getAllResourceData(): array
    {
        return $this->resourceIdData() + $this->getResourceData() + $this->getRelationships();
    }

    /**
     * @return array{id: mixed}|array{}
     */
    private function resourceIdData(): array
    {
        if ($this->resource instanceof Model) {
            return [
                'id' => $this->resource->getKey(),
            ];
        }

        return [];
    }

    /**
     * @return list<string>
     */
    protected function getRelationshipsNames(): array
    {
        if ($this->relationships === '' || $this->relationships === null) {
            return [];
        }

        $relationships = [];

        foreach (explode(',', trim($this->relationships)) as $relationship) {
            if ($relationship !== '') {
                $relationships[] = $relationship;
            }
        }

        return $relationships;
    }
}
