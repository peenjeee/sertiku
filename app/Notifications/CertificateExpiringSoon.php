<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateExpiringSoon extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $certificate)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('PENTING: Sertifikat Segera Kedaluwarsa - ' . $this->certificate->certificate_number)
            ->greeting('Halo, ' . $this->certificate->recipient_name)
            ->line('Kami ingin memberitahukan bahwa sertifikat Anda akan segera kedaluwarsa dalam 3 hari.')
            ->line('Detail Sertifikat:')
            ->line('Nama: ' . $this->certificate->recipient_name)
            ->line('Nomor Sertifikat: ' . $this->certificate->certificate_number)
            ->line('Program: ' . $this->certificate->course_name)
            ->line('Tanggal Kedaluwarsa: ' . $this->certificate->expire_date->format('d M Y'))
            ->action('Lihat Detail', route('verifikasi.show', $this->certificate->hash))
            ->line('Harap perhatikan tanggal kedaluwarsa tersebut.')
            ->line('Terima kasih.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'certificate_id' => $this->certificate->id,
            'certificate_number' => $this->certificate->certificate_number,
            'status' => 'expiring_soon',
            'title' => 'Sertifikat Segera Kedaluwarsa',
            'message' => 'Sertifikat ' . $this->certificate->course_name . ' akan kedaluwarsa dalam 3 hari.',
            'url' => route('verifikasi.show', $this->certificate->hash)
        ];
    }
}
