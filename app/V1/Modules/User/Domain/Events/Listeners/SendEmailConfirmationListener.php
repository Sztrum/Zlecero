<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Events\Listeners;

use App\V1\Modules\Email\Domain\Services\EmailService;
use App\V1\Modules\User\Domain\Events\UserHasBeenCreatedEvent;
use App\V1\Modules\User\Domain\Mail\VerifyEmailMail;

readonly class SendEmailConfirmationListener
{
    public function __construct(private EmailService $emailService)
    {
    }

    public function handle(UserHasBeenCreatedEvent $event): void
    {
        $this->emailService->sendEmail([$event->user->email], new VerifyEmailMail($event->user));
    }
}
