<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CertificateReceived extends Notification
{
    use Queueable;

    protected $certificate;

    /**
     * Create a new notification instance.
     */
    public function __construct($certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'               => 'certificate_received',
            'title'              => 'Sertifikat baru ditambahkan',
            'subtitle'           => $this->certificate->course_name ?? 'Sertifikat Baru',
            'certificate_id'     => $this->certificate->id,
            'certificate_number' => $this->certificate->certificate_number,
            'issuer'             => $this->certificate->user->institution_name ?? $this->certificate->user->name ?? 'Lembaga',
        ];
    }
}
