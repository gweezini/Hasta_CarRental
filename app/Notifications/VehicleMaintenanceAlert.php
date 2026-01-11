<?php

namespace App\Notifications;

use App\Models\Vehicle;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VehicleMaintenanceAlert extends Notification
{
    use Queueable;

    protected $vehicle;
    protected $booking;
    protected $reason;

    public function __construct(Vehicle $vehicle, $booking = null, $reason = 'Feedback issues reported')
    {
        $this->vehicle = $vehicle;
        $this->booking = $booking;
        $this->reason = $reason;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'vehicle_id' => $this->vehicle->id,
            'booking_id' => $this->booking ? $this->booking->id : null,
            'message' => "Vehicle {$this->vehicle->plate_number} has been set to UNAVAILABLE. Reason: {$this->reason}.",
            'category' => 'fleet_alert',
            'url' => route('admin.vehicle.edit', $this->vehicle->id),
        ];
    }
}
