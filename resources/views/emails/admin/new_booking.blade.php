<!DOCTYPE html>
<html>
<head>
    <title>New Booking Notification</title>
    <style>
        .header {
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid #ec5a29;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; overflow: hidden;">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/logo_hasta.jpeg')) }}" alt="Hasta Car Rental">
        </div>
        
        <h2 style="color: #ec5a29; text-align: center; margin-top: 0;">New Booking Received</h2>
        <p>Hello Admin,</p>
        <p>A new booking has just been placed. Please review and verify it.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Customer:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $booking->user->name }} ({{ $booking->user->phone_number }})</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Vehicle:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->plate_number }})</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Pickup:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ \Carbon\Carbon::parse($booking->pickup_date_time)->format('d M Y, h:i A') }} <br> ({{ $booking->pickup_location }})</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Return:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ \Carbon\Carbon::parse($booking->return_date_time)->format('d M Y, h:i A') }} <br> ({{ $booking->dropoff_location }})</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Rental Fee:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">RM {{ number_format($booking->total_rental_fee, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Deposit:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">RM {{ number_format($booking->deposit_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Grand Total:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee; color: #ec5a29; font-weight: bold;">RM {{ number_format($booking->total_rental_fee + $booking->deposit_amount, 2) }}</td>
            </tr>
        </table>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('admin.bookings.show_detail', $booking->id) }}" style="background-color: #ec5a29; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">View Booking</a>
        </div>
        
        <p style="text-align: center; color: #999; font-size: 0.8rem; margin-top: 30px;">This is an automated message from Hasta Car Rental System.</p>
    </div>
</body>
</html>
