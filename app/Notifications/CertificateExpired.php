<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateExpired extends Notification implements ShouldQueue
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
        // Only mail for recipient (unless we verify they are a User)
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('PENTING: Sertifikat Telah Kedaluwarsa - ' . $this->certificate->certificate_number)
            ->greeting('Halo, ' . $this->certificate->recipient_name)
            ->line('Kami ingin memberitahukan bahwa sertifikat Anda dengan detail berikut telah KEDALUWARSA hari ini:')
            ->line('Nama: ' . $this->certificate->recipient_name)
            ->line('Nomor Sertifikat: ' . $this->certificate->certificate_number)
            ->line('Program: ' . $this->certificate->course_name)
            ->line('Tanggal Kedaluwarsa: ' . $this->certificate->expire_date->format('d M Y'))
            ->action('Lihat Detail', route('verifikasi.show', $this->certificate->hash))
            ->line('Sertifikat ini mungkin tidak lagi valid untuk digunakan sebagai bukti kompetensi.')
            ->line('Silakan hubungi penerbit sertifikat jika Anda memiliki pertanyaan.')
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
            'status' => 'expired',
            'title' => 'Sertifikat Kedaluwarsa',
            'message' => 'Sertifikat ' . $this->certificate->course_name . ' telah kedaluwarsa.',
            'url' => route('verifikasi.show', $this->certificate->hash)
        ];
    }
}
