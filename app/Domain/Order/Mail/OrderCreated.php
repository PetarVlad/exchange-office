<?php

namespace App\Domain\Order\Mail;

use App\Domain\Order\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private string $defaultCurrency;

    public function __construct(public readonly Order $order){
        $this->defaultCurrency = config('currencies.default');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order #{$this->order->id} created",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order.created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
