<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ConflictException extends HttpException
{
    /**
     * @param array<string, bool|float|int|string|null> $replace
     */
    public function __construct(string $message = '', array $replace = [])
    {
        parent::__construct(__($message, $replace), Response::HTTP_CONFLICT);
    }
}
