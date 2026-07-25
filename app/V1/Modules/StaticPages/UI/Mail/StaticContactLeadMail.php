<?php

declare(strict_types=1);

namespace App\V1\Modules\StaticPages\UI\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaticContactLeadMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @param array{name: string, company: string, email: string, phone: string|null, subject: string, message: string} $lead
     */
    public function __construct(
        public readonly array $lead
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [$this->lead['email']],
            subject: 'Zlecero contact: '.$this->lead['subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'static_pages::_frontend.email.contact-lead',
            with: ['lead' => $this->lead],
        );
    }
}
