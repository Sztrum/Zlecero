<?php

declare(strict_types=1);

namespace App\V1\Shared\Domain\DTO;

abstract class AbstractRemoteFileInfoDTO extends AbstractDTO
{
    public function __construct(
        public string $uri,
        public int $size,
        public string $contentType,
    ) {
    }
}
