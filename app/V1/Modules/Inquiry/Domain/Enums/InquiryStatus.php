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

    /**
     * Shortest sequence of allowed transitions leading from this status to $target.
     *
     * Workflow steps such as PREPARING_OFFER are intermediate stages rather than
     * barriers, so a caller that legitimately reaches a later stage should walk
     * through them instead of being silently skipped.
     *
     * @return list<self>|null Ordered statuses to apply; empty when already at
     *                         $target, null when $target cannot be reached.
     */
    public function transitionPathTo(self $target): ?array
    {
        if ($this === $target) {
            return [];
        }

        $visited = [$this->value => true];
        /** @var list<list<self>> $queue */
        $queue = [[$this]];

        while ($queue !== []) {
            /** @var list<self> $path */
            $path = array_shift($queue);
            $current = $path[count($path) - 1];

            foreach ($current->allowedNextStatuses() as $nextStatus) {
                if (isset($visited[$nextStatus->value])) {
                    continue;
                }

                $visited[$nextStatus->value] = true;
                $nextPath = [...$path, $nextStatus];

                if ($nextStatus === $target) {
                    return array_slice($nextPath, 1);
                }

                $queue[] = $nextPath;
            }
        }

        return null;
    }
}
