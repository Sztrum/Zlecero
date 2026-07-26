<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Enums;

enum FrontEndRouteEnum: string
{
    case AUTH_LOGIN = '/login';
    case AUTH_REGISTER = '/auth/register';
    case AUTH_VERIFY_EMAIL = '/auth/verify-email';
    case AUTH_RESET_PASSWORD = '/auth/reset-password/{remember_token}/{token}';
}
