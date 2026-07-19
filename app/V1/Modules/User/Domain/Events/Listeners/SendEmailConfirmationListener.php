<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Events\Listeners;

use App\V1\Modules\Email\Domain\Services\EmailService;
use App\V1\Modules\User\Domain\Events\UserHasBeenCreatedEvent;
use App\V1\Modules\User\Domain\Mail\VerifyEmailMail;

readonly class SendEmailConfirmationListener
{
    public function handle(UserHasBeenCreatedEvent|ResendEmailConfirmationEvent $event): void
    {
        /** @var EmailService $emailService */
        $emailService = resolve(EmailService::class);

        $emailService->sendEmail([$event->user->email], new VerifyEmailMail($event->user));
    }
}
