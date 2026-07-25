<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\Domain\Enums;

enum OrderStatus: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}
