<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer\Domain\Enums;

enum OfferDiscountType: string
{
    case PERCENT = 'percent';
    case AMOUNT = 'amount';
}
