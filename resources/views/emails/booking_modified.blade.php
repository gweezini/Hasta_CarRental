@component('mail::message')
# Booking Updated

Hello **{{ $notifiable->name }}**,

Your booking **#{{ $booking->id }}** has been modified by the administrator.

**Reason/Changes:**
{{ $changeMessage }}

### Updated Booking Details:
- **Vehicle:** {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->plate_number }})
- **Pickup:** {{ \Carbon\Carbon::parse($booking->pickup_date_time)->format('d M Y, h:i A') }}
- **Return:** {{ \Carbon\Carbon::parse($booking->return_date_time)->format('d M Y, h:i A') }}

@component('mail::button', ['url' => route('profile.edit')])
View My Bookings
@endcomponent

If you have any questions regarding this change, please contact our support team.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
