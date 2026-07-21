<?php

declare(strict_types=1);

namespace App\V1\Modules\Email\Domain\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailer;

readonly class EmailService
{
    public function __construct(
        private Mailer $mailer,
    ) {
    }

    /**
     * @param list<string> $recipients
     */
    public function sendEmail(
        array $recipients,
        Mailable $mailable,
    ): void {
        $this->mailer->to($recipients)->send($mailable);
    }
}
