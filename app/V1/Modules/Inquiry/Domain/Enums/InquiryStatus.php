<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\Domain\Enums;

enum InquiryStatus: string
{
    case NEW = 'new';
    case TRIAGE = 'triage';
    case WAITING_FOR_CUSTOMER = 'waiting_for_customer';
    case PREPARING_OFFER = 'preparing_offer';
    case OFFER_SENT = 'offer_sent';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case CLOSED = 'closed';

    /**
     * @return list<self>
     */
    public function allowedNextStatuses(): array
    {
        return match ($this) {
            self::NEW => [self::TRIAGE, self::WAITING_FOR_CUSTOMER, self::PREPARING_OFFER, self::CLOSED],
            self::TRIAGE => [self::WAITING_FOR_CUSTOMER, self::PREPARING_OFFER, self::CLOSED],
            self::WAITING_FOR_CUSTOMER => [self::TRIAGE, self::PREPARING_OFFER, self::CLOSED],
            self::PREPARING_OFFER => [self::OFFER_SENT, self::WAITING_FOR_CUSTOMER, self::CLOSED],
            self::OFFER_SENT => [self::ACCEPTED, self::REJECTED, self::PREPARING_OFFER, self::CLOSED],
            self::ACCEPTED, self::REJECTED => [self::CLOSED],
            self::CLOSED => [],
        };
    }

    public function canTransitionTo(self $status): bool
    {
        foreach ($this->allowedNextStatuses() as $allowedStatus) {
            if ($allowedStatus === $status) {
                return true;
            }
        }

        return false;
    }
}
