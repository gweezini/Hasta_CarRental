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
        

        $level = $isApproved ? 'success' : 'error';
        
        $subject = $isApproved 
            ? '✅ Booking Confirmed: #' . $this->booking->id 
            : '❌ Payment Rejected: #' . $this->booking->id;

        // 2. 设置具体内容
        if ($isApproved) {
            $lines = [
                'Great news! Your payment has been verified.',
                'Vehicle: ' . $this->booking->vehicle->brand . ' ' . $this->booking->vehicle->model . ' (' . $this->booking->vehicle->plate_number . ')',
                'Pickup Date: ' . Carbon::parse($this->booking->pickup_date_time)->format('d M Y, h:i A'),
                'Pickup Location: ' . $this->booking->pickup_location,
                'Please pick up your vehicle on time.'
            ];
            $actionText = 'View Booking Receipt';
        } else {
            $lines = [
                'We are sorry to inform you that your payment verification was REJECTED.',
                'Reason: The receipt uploaded was unclear or the amount was incorrect.',
                'Please login to your profile to upload a new receipt or contact admin.',
                'Vehicle: ' . $this->booking->vehicle->brand . ' ' . $this->booking->vehicle->model
            ];
            $actionText = 'Check Booking Status';
        }

        $mailMessage = (new MailMessage)
                    ->subject($subject)
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->level($level); 

        foreach ($lines as $line) {
            $mailMessage->line($line);
        }

        return $mailMessage->action($actionText, route('profile.edit'))
                           ->line('Thank you for choosing Hasta Car Rental!');
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