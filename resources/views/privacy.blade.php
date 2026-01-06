<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Hasta Car Rental</title>
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
        <h1>Privacy Policy</h1>
        <p>Your privacy is important to us.</p>
    </div>

    <div class="container">
        <div class="content_card">
            <h2>1. Data Collection</h2>
            <p>To facilitate your booking and ensure a secure rental experience, we collect specific personal information through our digital platform:</p>
            <ul>
                <li><strong>Profile Information:</strong> Your name, email address, and phone number used to create your "Ali Student" account.</li>
                <li><strong>Emergency Contact:</strong> We require the Name, Phone Number, and Relationship of an emergency contact (e.g., parent or guardian) during the checkout process.</li>
                <li><strong>Financial Information:</strong> We collect your <strong>Bank Name</strong> and <strong>Account Number</strong> specifically to process secure security deposit refunds.</li>
            </ul>

            <h2>2. How We Use Your Data</h2>
            <ul>
                <li><strong>Booking Management:</strong> To process your reservation, verify availability, and manage vehicle handovers.</li>
                <li><strong>Refund Processing:</strong> Your banking details are used strictly to return your security deposit after the rental period concludes successfully.</li>
                <li><strong>Communication:</strong> To send booking confirmations, reminders, and modification updates to your dashboard.</li>
            </ul>

            <h2>3. Data Protection</h2>
            <p>We are committed to securing your data. Your banking information is stored securely and is only accessed for the purpose of processing authorized refunds.</p>
        </div>
    </div>

</body>
</html>
