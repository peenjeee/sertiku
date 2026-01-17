<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringSoon extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $user)
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
        $expiryDate = $this->user->subscription_expires_at->format('d M Y');
        $packageName = $this->user->model_package?->name ?? 'Premium';

        return (new MailMessage)
            ->subject('PENTING: Paket Langganan Segera Berakhir')
            ->greeting('Halo, ' . $this->user->name)
            ->line("Kami ingin memberitahukan bahwa paket langganan {$packageName} Anda akan berakhir dalam 3 hari pada tanggal {$expiryDate}.")
            ->line('Segera lakukan perpanjangan untuk memastikan layanan penerbitan sertifikat Anda tidak terganggu.')
            ->action('Perpanjang Sekarang', route('pricing'))
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Langganan Segera Berakhir',
            'message' => 'Paket langganan Anda akan berakhir pada ' . $this->user->subscription_expires_at->format('d M Y'),
            'type' => 'subscription_expiry',
            'url' => route('pricing')
        ];
    }
}
