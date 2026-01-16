<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateRevoked extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $certificate, public $reason)
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('PENTING: Sertifikat Dicabut - ' . $this->certificate->certificate_number)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Kami ingin memberitahukan bahwa sertifikat Anda dengan detail berikut telah DICABUT oleh penerbit:')
            ->line('Nama: ' . $this->certificate->recipient_name)
            ->line('Nomor Sertifikat: ' . $this->certificate->certificate_number)
            ->line('Program: ' . $this->certificate->course_name)
            ->line('Alasan Pencabutan: ' . $this->reason)
            ->action('Lihat Detail', route('verifikasi.show', $this->certificate->hash))
            ->line('Sertifikat ini tidak lagi valid untuk digunakan.')
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
            'status' => 'revoked',
            'reason' => $this->reason,
            'image' => $this->certificate->user->avatar ?? null, // Lembaga avatar
            'title' => 'Sertifikat Dicabut',
            'message' => 'Sertifikat ' . $this->certificate->course_name . ' telah dicabut: ' . $this->reason,
            'url' => route('verifikasi.show', $this->certificate->hash)
        ];
    }
}
