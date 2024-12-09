<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Application\Providers;

use App\V1\Core\Application\Providers\EventServiceProvider;
use App\V1\Modules\User\Domain\Events\Subscribers\UserEventSubscriber;

class UserEventServiceProvider extends EventServiceProvider
{
    protected $subscribe = [
        UserEventSubscriber::class,
    ];
}
