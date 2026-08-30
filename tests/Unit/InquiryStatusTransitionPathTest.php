<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\V1\Modules\Inquiry\Domain\Enums\InquiryStatus;
use PHPUnit\Framework\TestCase;

class InquiryStatusTransitionPathTest extends TestCase
{
    public function test_it_walks_through_intermediate_stages_when_there_is_no_direct_transition(): void
    {
        self::assertSame(
            [InquiryStatus::PREPARING_OFFER, InquiryStatus::OFFER_SENT],
            InquiryStatus::NEW->transitionPathTo(InquiryStatus::OFFER_SENT),
        );

        self::assertSame(
            [InquiryStatus::PREPARING_OFFER, InquiryStatus::OFFER_SENT, InquiryStatus::ACCEPTED],
            InquiryStatus::TRIAGE->transitionPathTo(InquiryStatus::ACCEPTED),
        );
    }

    public function test_it_returns_a_single_step_for_an_allowed_direct_transition(): void
    {
        self::assertSame(
            [InquiryStatus::ACCEPTED],
            InquiryStatus::OFFER_SENT->transitionPathTo(InquiryStatus::ACCEPTED),
        );
    }

    public function test_it_returns_an_empty_path_when_the_target_is_already_reached(): void
    {
        self::assertSame([], InquiryStatus::ACCEPTED->transitionPathTo(InquiryStatus::ACCEPTED));
    }

    public function test_it_reports_unreachable_targets_instead_of_pretending_to_succeed(): void
    {
        self::assertNull(InquiryStatus::CLOSED->transitionPathTo(InquiryStatus::OFFER_SENT));
        self::assertNull(InquiryStatus::ACCEPTED->transitionPathTo(InquiryStatus::OFFER_SENT));
        self::assertNull(InquiryStatus::REJECTED->transitionPathTo(InquiryStatus::ACCEPTED));
    }

    public function test_every_returned_path_only_uses_allowed_transitions(): void
    {
        foreach (InquiryStatus::cases() as $from) {
            foreach (InquiryStatus::cases() as $target) {
                $path = $from->transitionPathTo($target);

                if ($path === null || $path === []) {
                    continue;
                }

                $current = $from;

                foreach ($path as $nextStatus) {
                    self::assertTrue(
                        $current->canTransitionTo($nextStatus),
                        sprintf(
                            'Path from %s to %s used the forbidden step %s -> %s.',
                            $from->value,
                            $target->value,
                            $current->value,
                            $nextStatus->value,
                        ),
                    );

                    $current = $nextStatus;
                }

                self::assertSame($target, $current);
            }
        }
    }
}
