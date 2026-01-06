<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Hasta Car Rental</title>
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
        <h1>About Us</h1>
        <p>Your premier car rental service in UTM.</p>
    </div>

    <div class="container">
        <div class="content_card">
            <h2>Who We Are</h2>
            <p>Hasta Car Rental is your premier "Affordable Car Rental Service" provider, specifically designed to serve the community in and around the UTM Campus. We combine technology with mobility to offer a seamless, self-service car rental experience right from your dashboard.</p>

            <h2>Our Fleet & Pricing</h2>
            <p>We specialize in reliable compact vehicles like the <strong>Perodua Axia</strong>, perfect for city driving and student budgets. We believe in fair and transparent pricing:</p>
            <ul>
                <li><strong>Flexible Hours:</strong> Rent for as little as 1 hour or for multiple days.</li>
                <li><strong>Cumulative Rates:</strong> Our smart pricing model ensures you get the best deal. For example, a 25-hour rental is calculated as the 24-hour rate plus the 1-hour rate, rather than a flat expensive fee.</li>
            </ul>

            <h2>Why Use Our Platform?</h2>
            <ul>
                <li><strong>Digital Convenience:</strong> Manage your entire journey online. From viewing rates to modifying your booking, everything is available at your fingertips.</li>
                <li><strong>Student Centric:</strong> Our primary pickup hub is the <strong>Student Mall at UTM</strong>, offering free pickup for easy campus access.</li>
                <li><strong>Secure & Trustworthy:</strong> We provide a secure environment for bookings with clear refund processes handled directly through your user profile.</li>
            </ul>

            <h2>Contact Us</h2>
            <ul>
                <li><strong>Inquiry:</strong> +60 11-1090 0700</li>
                <li><strong>Email:</strong> hastatraveltours@gmail.com</li>
            </ul>
        </div>
    </div>

</body>
</html>
