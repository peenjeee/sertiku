<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CertificateViewed extends Notification
{
    use Queueable;

    protected $certificate;
    protected $viewerInfo;

    /**
     * Create a new notification instance.
     */
    public function __construct($certificate, $viewerInfo = null)
    {
        $this->certificate = $certificate;
        $this->viewerInfo  = $viewerInfo;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'               => 'certificate_viewed',
            'title'              => 'Sertifikat dilihat',
            'subtitle'           => $this->certificate->course_name ?? 'Sertifikat',
            'certificate_id'     => $this->certificate->id,
            'certificate_number' => $this->certificate->certificate_number,
            'viewer_info'        => $this->viewerInfo,
        ];
    }
}
