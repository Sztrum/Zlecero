<?php

declare(strict_types=1);

namespace App\V1\Modules\Email\Application\Providers;

use App\V1\Modules\Email\Domain\Services\EmailService;
use Illuminate\Mail\Mailer;
use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EmailService::class, fn () => new EmailService(
            $this->app->get(Mailer::class),
        ));
    }
}
