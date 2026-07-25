<?php

declare(strict_types=1);

namespace App\V1\Modules\Company\Domain\Enums;

enum CompanyUserRole: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case MEMBER = 'member';
}
