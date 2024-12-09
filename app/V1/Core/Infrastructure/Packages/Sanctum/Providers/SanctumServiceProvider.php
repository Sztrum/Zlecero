<?php

declare(strict_types=1);

namespace App\V1\Core\Infrastructure\Packages\Sanctum\Providers;

use App\V1\Core\Infrastructure\Packages\Sanctum\Models\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class SanctumServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
