<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Events\Subscribers;

use App\V1\Modules\User\Domain\Events\Listeners\SendEmailConfirmationListener;
use App\V1\Modules\User\Domain\Events\UserHasBeenCreatedEvent;
use Illuminate\Contracts\Events\Dispatcher;

class UserEventSubscriber
{
    public function subscribe(Dispatcher $dispatcher): void
    {
        $dispatcher->listen(
            events: [UserHasBeenCreatedEvent::class, ResendEmailConfirmationEvent::class],
            listener: SendEmailConfirmationListener::class
        );
    }
}
