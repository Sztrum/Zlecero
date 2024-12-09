<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\Domain\DTO;

use Spatie\LaravelData\Data;

class LoginDataDTO extends Data
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
