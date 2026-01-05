<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;
use Carbon\Carbon;

class BookingStatusUpdated extends Notification
{
    use Queueable;

    public $booking;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, $status)
    {
        $this->booking = $booking;
        $this->status = $status;
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
        $isApproved = $this->status === 'Approved';
        $reason = $isApproved ? null : ($this->booking->rejection_reason ?? 'Unclear receipt or incorrect amount.');
        
        $subject = $isApproved 
            ? '✅ Booking Confirmed: #' . $this->booking->id 
            : 'Booking Rejected: #' . $this->booking->id;

        return (new MailMessage)
            ->subject($subject)
            ->markdown('emails.booking_status', [
                'booking' => $this->booking,
                'isApproved' => $isApproved,
                'reason' => $reason,
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
            'status' => $this->status,
            'message' => 'Your booking #' . $this->booking->id . ' has been ' . strtoupper($this->status) . '.',
            'time' => now()
        ];
    }
}