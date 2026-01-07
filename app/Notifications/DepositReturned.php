<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositReturned extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Store in database for notification bell
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Deposit Returned',
            'message' => 'Your deposit for booking #' . $this->booking->id . ' has been returned.',
            'booking_id' => $this->booking->id,
            'type' => 'success', // For icon styling
        ];
    }
}
