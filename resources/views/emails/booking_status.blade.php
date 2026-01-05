<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status Update</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 3px solid #ec5a29;
        }
        .header img {
            max-width: 180px;
            height: auto;
        }
        .content {
            padding: 40px 30px;
            color: #333333;
        }
        .status-badge {
            text-align: center;
            margin-bottom: 30px;
            margin-top: 50px;
        }
        .status-pill {
            display: inline-block;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .status-approved {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .message {
            font-size: 16px;
            line-height: 1.6;
            text-align: center;
            margin-bottom: 30px;
            color: #555555;
        }
        .details-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 30px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            color: #64748b;
            font-weight: 500;
        }
        .detail-value {
            color: #1e293b;
            font-weight: 700;
            text-align: right;
        }
        .inspection-reminder {
            background-color: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 30px;
            text-align: center;
        }
        .inspection-reminder strong {
            color: #92400e;
            display: block;
            margin-bottom: 4px;
        }
        .inspection-reminder span {
            color: #b45309;
            font-size: 14px;
        }
        .action-btn {
            display: block;
            width: 100%;
            background-color: #ec5a29;
            color: #ffffff;
            text-align: center;
            padding: 16px 0;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: opacity 0.2s;
        }
        .action-btn:hover {
            opacity: 0.9;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/logo_hasta.jpeg')) }}" alt="Hasta Car Rental">
        </div>
        
        <div class="content">
            <div class="status-badge">
                <span class="status-pill {{ $isApproved ? 'status-approved' : 'status-rejected' }}">
                    {{ $isApproved ? 'Booking Confirmed' : 'Booking Unsuccessful' }}
                </span>
            </div>

            <div class="message">
                HI, <strong>{{ $notifiable->name }}</strong><br><br>
                {{ $isApproved 
                    ? 'Your payment has been successfully verified and approved.' 
                    : 'Sorry, since ' . ($reason ?? 'requirements were not met') . ', your booking has been rejected. Please login to see the details.' 
                }}
            </div>



            @if($isApproved)
            <h3 style="text-align: center; color: #333; margin-bottom: 15px; font-size: 18px;">Booking details:</h3>
            @endif

            <div class="details-card" style="padding: 0; background-color: transparent; border: none;">
                <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; border-collapse: separate; border-spacing: 0; overflow: hidden;">
                    <tr>
                        <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 500; width: 40%;">Booking Reference</td>
                        <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 700; text-align: right;">#{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 500;">Vehicle</td>
                        <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 700; text-align: right;">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 500;">Plate Number</td>
                        <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 700; text-align: right; text-transform: uppercase;">{{ $booking->vehicle->plate_number }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 500;">Pickup</td>
                        <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 700; text-align: right;">
                            {{ \Carbon\Carbon::parse($booking->pickup_date_time)->format('d M Y') }}
                            <div style="font-weight: 400; font-size: 13px; color: #64748b; margin-top: 2px;">{{ \Carbon\Carbon::parse($booking->pickup_date_time)->format('h:i A') }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 20px; color: #64748b; font-weight: 500;">Return</td>
                        <td style="padding: 16px 20px; color: #1e293b; font-weight: 700; text-align: right;">
                            {{ \Carbon\Carbon::parse($booking->return_date_time)->format('d M Y') }}
                            <div style="font-weight: 400; font-size: 13px; color: #64748b; margin-top: 2px;">{{ \Carbon\Carbon::parse($booking->return_date_time)->format('h:i A') }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            @if($isApproved)
            <div class="inspection-reminder">
                <strong>⚠️ Kindly Reminder:</strong>
                <span>Don't forget to upload the vehicle inspection photos before driving the car. Thank you for your cooperation!</span>
            </div>
            @endif

            <a href="{{ route('profile.edit') }}" class="action-btn">
                {{ $isApproved ? 'View Booking & Upload Inspection' : 'View Details' }}
            </a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            Need help? Contact our support.
        </div>
    </div>
</body>
</html>
