<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable;

    use InteractsWithQueue;

    use Queueable;

    use SerializesModels;

    public bool $deleteWhenMissingModels = true;

    public int $tries = 3;

    public function __construct(
        private Mailable $mailable,
        private string $email
    ) {
    }

    public function handle(): void
    {
        Mail::to($this->email)->send($this->mailable);
    }
}
