<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $order;
    public $subtotal;
    public $total;
    public function __construct($order)
    {
        $this->order = $order;
        $this->subtotal = $this->calculateSubtotal();
        $this->total = $this->calculateTotal();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Email',
        );
    }
    public function calculateSubtotal()
    {
        $subtotal = 0;

        foreach ($this->order->orderItems as $orderItem) {
            $subtotal += $orderItem->quantity * $orderItem->price;
        }

        return $subtotal;
    }

    public function calculateTotal()
    {
        $subtotal = $this->calculateSubtotal();
        $couponValue = $this->order->coupon_discount;
        $total = $subtotal - $couponValue;

        return $total;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'OrderView',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    public function timeout(): int
    {
        return 60; // Set timeout to 60 seconds
    }
}
