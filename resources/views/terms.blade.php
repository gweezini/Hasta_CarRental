<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions - Hasta Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Syncopate:wght@400;700&display=swap");

        :root {
            --primary-color: #ec5a29;
            --text-dark: #15191d;
            --text-light: #737373;
            --white: #ffffff;
            --bg-gray: #f9fafb;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "Poppins", sans-serif;
            background-color: #ffffff;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .header {
            position: relative;
            background-color: #2d3748;
            padding: 4rem 2rem;
            text-align: center;
            color: var(--white);
        }

        .header h1 {
            font-family: "Syncopate", sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .container {
            max-width: 800px;
            margin: -3rem auto 5rem;
            padding: 0 1.5rem;
            position: relative;
            z-index: 10;
        }

        .content_card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 2.5rem;
        }

        h2 { margin-top: 1.5rem; margin-bottom: 0.5rem; font-size: 1.25rem; color: var(--text-dark); }
        p, li { color: var(--text-light); margin-bottom: 1rem; font-size: 0.95rem; }
        ul { margin-left: 1.5rem; margin-bottom: 1.5rem; }
        strong { color: var(--text-dark); }
        
        .back-btn {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: var(--white);
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            z-index: 20;
        }

        .back-btn:hover { color: var(--primary-color); }
    </style>
</head>
<body>

    <div class="header">
        <a href="{{ url()->previous() }}" class="back-btn">
            <i class="ri-arrow-left-line"></i> Back
        </a>
        <h1>Terms & Conditions</h1>
        <p>Please read our terms carefully.</p>
    </div>

    <div class="container">
        <div class="content_card">
            <h2>1. Booking & Payments</h2>
            <ul>
                <li><strong>Time Limit:</strong> When making a reservation, you must complete the booking process within the allotted time (approx. 10 minutes) as displayed on the countdown timer, or your session may expire.</li>
                <li><strong>Rates:</strong> Rental rates are calculated cumulatively. Longer durations automatically benefit from our daily capping structure (e.g., 24 hours = RM 120.00).</li>
                <li><strong>Vouchers:</strong> Valid voucher codes must be entered or selected at the checkout page to redeem discounts. Discounts cannot be applied after payment is confirmed.</li>
            </ul>

            <h2>2. Pickup & Return</h2>
            <ul>
                <li><strong>Primary Location:</strong> The standard pickup point is <strong>Student Mall, UTM</strong>. Pickup at this location is free of charge.</li>
                <li><strong>Delivery Fees:</strong> Requests to pick up vehicles outside the Student Mall area will incur additional delivery fees.</li>
                <li><strong>Schedule:</strong> You must adhere to the pickup and return times selected. Late returns may result in additional hourly charges based on our standard rates.</li>
            </ul>

            <h2>3. Modifications & Cancellations</h2>
            <ul>
                <li><strong>Manage Booking:</strong> You can view, modify, or cancel your upcoming bookings directly through the "Ongoing Bookings" dashboard.</li>
                <li><strong>Cancellation Policy:</strong> Please refer to the specific cancellation terms visible in your dashboard. Cancellations made at the last minute may forfeit the deposit.</li>
            </ul>

            <h2>4. Security Deposit & Refunds</h2>
            <ul>
                <li><strong>Requirement:</strong> A security deposit is mandatory for all rentals.</li>
                <li><strong>Refund Process:</strong> You must provide accurate bank account details in the "Payment & Refund" section. Refunds are processed to this account after the vehicle is returned and inspected.</li>
                <li><strong>Forfeiture:</strong> The deposit may be used to cover damages, fuel shortages, or late return penalties.</li>
            </ul>

            <h2>5. Emergency Contact</h2>
            <p>You agree to provide accurate emergency contact details (e.g., Father, Guardian) prior to payment. We reserve the right to contact this person in the event of an emergency or if the vehicle is not returned on time.</p>
        </div>
    </div>

</body>
</html>
