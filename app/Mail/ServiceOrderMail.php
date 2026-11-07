<?php

namespace App\Mail;

use App\Models\ServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ServiceOrder $order,
        public string $subjectText,
        public string $headline,
        public string $detailMessage,
        public ?string $actionUrl = null
    ) {
        $this->actionUrl = $actionUrl ?? url('/user-panel?ticket=' . $this->order->ticket_no);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[{$this->order->ticket_no}] {$this->subjectText} - SerComp Digital",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.service-order',
        );
    }
}
