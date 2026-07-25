<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\Domain\Enums;

enum InquiryMessageDirection: string
{
    case INBOUND = 'inbound';
    case OUTBOUND = 'outbound';
    case INTERNAL = 'internal';
}
