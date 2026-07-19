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

    public $deleteWhenMissingModels = true;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Mailable $mailable,
        private string $email
    ) {
        //
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    //    public function middleware()
    //    {
    //        return [(new ThrottlesExceptions(3, 20))->backoff(5)];
    //    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send($this->mailable);
    }
}
