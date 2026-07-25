<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\Domain\Enums;

enum InquiryPriority: string
{
    case LOW = 'low';
    case NORMAL = 'normal';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public function weight(): int
    {
        return match ($this) {
            self::URGENT => 4,
            self::HIGH => 3,
            self::NORMAL => 2,
            self::LOW => 1,
        };
    }
}
