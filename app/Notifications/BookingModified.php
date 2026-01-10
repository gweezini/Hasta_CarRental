<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingModified extends Notification
{
    use Queueable;

    public $booking;
    public $changeMessage;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, $changeMessage)
    {
        $this->booking = $booking;
        $this->changeMessage = $changeMessage;
    }

    /**
     * Get the notification's delivery channels.
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
            ->subject('⚠️ Booking Updated: #' . $this->booking->id)
            ->markdown('emails.booking_modified', [
                'booking' => $this->booking,
                'changeMessage' => $this->changeMessage,
                'notifiable' => $notifiable
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'Your booking #' . $this->booking->id . ' has been updated: ' . $this->changeMessage,
            'time' => now()
        ];
    }
}
