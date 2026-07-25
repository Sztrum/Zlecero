<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer\Domain\Enums;

enum OfferStatus: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}
