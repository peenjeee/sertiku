<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateReactivated extends Notification implements ShouldQueue
{
    use Queueable;

    public $timeout = 0; // Unlimited timeout

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
        return $notifiable instanceof \App\Models\User ? ['mail', 'database'] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('KABAR BAIK: Sertifikat Aktif Kembali - ' . $this->certificate->certificate_number)
            ->greeting('Halo, ' . ($notifiable->name ?? $this->certificate->recipient_name))
            ->line('Kami ingin memberitahukan bahwa sertifikat Anda yang sebelumnya dicabut, kini telah DIAKTIFKAN KEMBALI.')
            ->line('Detail Sertifikat:')
            ->line('Nama: ' . $this->certificate->recipient_name)
            ->line('Nomor Sertifikat: ' . $this->certificate->certificate_number)
            ->line('Program: ' . $this->certificate->course_name)
            ->action('Lihat Sertifikat', route('verifikasi.show', $this->certificate->hash))
            ->line('Sertifikat ini sekarang valid dan dapat digunakan kembali.')
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
            'status' => 'active',
            'image' => $this->certificate->user->avatar ?? null, // Lembaga avatar
            'title' => 'Sertifikat Diaktifkan',
            'message' => 'Sertifikat ' . $this->certificate->course_name . ' telah diaktifkan kembali.',
            'url' => route('verifikasi.show', $this->certificate->hash)
        ];
    }
}
