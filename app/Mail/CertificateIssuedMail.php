<?php

namespace App\Mail;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;

class CertificateIssuedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $timeout = 0; // Unlimited timeout

    public Certificate $certificate;
    public string $recipientName;
    public string $courseName;
    public string $issuerName;
    public string $certificateNumber;
    public string $issueDate;
    public ?string $expireDate;
    public string $verificationUrl;
    public ?string $downloadUrl;
    public bool $isRegistered = false;

    /**
     * Create a new message instance.
     */
    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
        $this->recipientName = $certificate->recipient_name;
        $this->courseName = $certificate->course_name ?? 'Sertifikat';
        $this->issuerName = $certificate->user->institution_name ?? $certificate->user->name ?? 'Lembaga';
        $this->certificateNumber = $certificate->certificate_number;
        $this->issueDate = $certificate->issue_date
            ? \Carbon\Carbon::parse($certificate->issue_date)->translatedFormat('d F Y')
            : now()->translatedFormat('d F Y');
        $this->expireDate = $certificate->expire_date
            ? \Carbon\Carbon::parse($certificate->expire_date)->translatedFormat('d F Y')
            : null;
        $this->verificationUrl = $certificate->verification_url;
        $this->downloadUrl = $certificate->pdf_url;
        // Check if recipient is already registered
        $this->isRegistered = \App\Models\User::where('email', $certificate->recipient_email)->exists();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sertifikat Anda Telah Diterbitkan - ' . $this->courseName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate-issued',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $attachments = [];

        // Use 'local' disk (private) as PDFs are now secured
        if ($this->certificate->pdf_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($this->certificate->pdf_path)) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorageDisk(
                'local',
                $this->certificate->pdf_path
            )->as('Sertifikat-' . $this->certificate->certificate_number . '.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
