<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Enums;

enum EnvironmentEnum: string
{
    case LOCAL = 'local';
    case PRODUCTION = 'production';
}
