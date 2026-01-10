<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #cb5c55; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { color: #cb5c55; margin: 0; font-size: 24px; }
        .content { padding: 10px 0; }
        .details { background: #f9f9f9; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #cb5c55; }
        .details-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .details-label { font-weight: bold; color: #666; width: 150px; }
        .details-value { font-weight: bold; color: #1a1a1a; flex: 1; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; }
        .badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .badge-cancelled { background: #fee2e2; color: #b91c1c; }
        .alert { background: #fffbeb; border: 1px solid #fef3c7; color: #92400e; padding: 15px; border-radius: 8px; font-weight: bold; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Cancelled</h1>
            <p>Notification for Admin & Top Management</p>
        </div>
        
        <div class="content">
            <p>Attention Admin,</p>
            <p>A booking has been <strong>Cancelled</strong> by the customer. Please review the details below, especially if a refund is required.</p>
            
            <div class="details">
                <div class="details-row">
                    <span class="details-label">Booking ID:</span>
                    <span class="details-value">#{{ $booking->id }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Customer:</span>
                    <span class="details-value">{{ $booking->user->name }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Vehicle:</span>
                    <span class="details-value">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->plate_number }})</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Total Amount:</span>
                    <span class="details-value">RM {{ number_format($booking->total_rental_fee + $booking->deposit_amount, 2) }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Payment Status:</span>
                    <span class="details-value">
                        @if($booking->payment_verified)
                            <span style="color: #059669;">Verified (Refund Needed)</span>
                        @else
                            <span style="color: #6b7280;">Unverified / Not Paid</span>
                        @endif
                    </span>
                </div>
            </div>

            @if($booking->payment_verified)
                <div class="alert">
                    ⚠️ ACTION REQUIRED: This booking was previously paid. Please process the refund as soon as possible.
                </div>
            @endif

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/admin/bookings/' . $booking->id . '/show') }}" style="background-color: #cb5c55; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">View Booking Details</a>
            </div>
        </div>
        
        <div class="footer">
            &copy; 2026 Hasta Car Rental. This is an automated notification.
        </div>
    </div>
</body>
</html>
