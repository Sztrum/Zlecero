<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\Domain\Enums;

enum CustomerType: string
{
    case COMPANY = 'company';
    case INDIVIDUAL = 'individual';
}
