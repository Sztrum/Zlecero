<?php

declare(strict_types=1);

namespace App\V1\Modules\Company\Domain\Enums;

enum CompanyUserStatus: string
{
    case INVITED = 'invited';
    case ACTIVE = 'active';
    case DEACTIVATED = 'deactivated';
}
