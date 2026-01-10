<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Fine;

class FineIssued extends Notification
{
    use Queueable;

    protected $fine;

    /**
     * Create a new notification instance.
     */
    public function __construct(Fine $fine)
    {
        $this->fine = $fine;
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
            'booking_id' => $this->fine->booking_id,
            'message' => 'You have been issued a PENALTY of RM ' . number_format($this->fine->amount, 2),
            'status' => 'Rejected', // Uses red icon logic in frontend
            'type' => 'fine'
        ];
    }
}
