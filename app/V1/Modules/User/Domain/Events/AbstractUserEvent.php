<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Events;

use App\V1\Modules\User\Domain\Models\User;

abstract class AbstractUserEvent
{
    public function __construct(
        public readonly User $user
    ) {
    }
}
