<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Define the public property so the view can see it
    public $token;

    /**
     * 2. Accept the token in the constructor
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Registration OTP Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            // 3. Explicitly pass the token to the view
            with: ['token' => $this->token],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
