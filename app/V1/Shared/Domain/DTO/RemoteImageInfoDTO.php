<?php

declare(strict_types=1);

namespace App\V1\Shared\Domain\DTO;

class RemoteImageInfoDTO extends AbstractRemoteFileInfoDTO
{
    public function __construct(
        public string $uri,
        public int $imageWidth,
        public int $imageHeight,
        public int $size,
        public string $contentType,
    ) {
        parent::__construct(
            uri: $uri,
            size: $size,
            contentType: $contentType,
        );
    }
}
