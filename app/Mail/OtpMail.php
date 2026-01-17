<?php

namespace App\Mail;

use App\Models\EmailVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;

class OtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $timeout = 0; // Unlimited timeout

    public string $otp;
    public string $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(EmailVerification $verification)
    {
        $this->otp = $verification->otp;
        $this->userName = $verification->user->name ?? 'User';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi OTP - SertiKu',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
