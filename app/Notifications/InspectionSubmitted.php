<?php

namespace App\Notifications;

use App\Models\Inspection;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InspectionSubmitted extends Notification
{
    use Queueable;

    protected $inspection;

    public function __construct(Inspection $inspection)
    {
        $this->inspection = $inspection;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $type = ucfirst($this->inspection->type);
        $plate = $this->inspection->booking->vehicle->plate_number;
        $customer = $this->inspection->booking->user->name;

        return [
            'inspection_id' => $this->inspection->id,
            'booking_id' => $this->inspection->booking_id,
            'message' => "📋 New {$type} Inspection submitted by {$customer} for {$plate}.",
            'category' => 'inspection_submission',
            'url' => route('admin.bookings.show_detail', $this->inspection->booking_id),
        ];
    }
}
