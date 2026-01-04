<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Restricted - Hasta Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ec5a29;
            --text-dark: #15191d;
            --white: #ffffff;
            --gray: #f3f4f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: rgba(0, 0, 0, 0.5);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-container {
            background: var(--white);
            width: 100%;
            max-width: 500px;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: #fee2e2;
            color: #dc2626;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 24px;
        }

        h2 {
            color: var(--text-dark);
            margin-bottom: 16px;
            font-size: 24px;
            font-weight: 700;
        }

        .reason-box {
            background: var(--gray);
            border-left: 4px solid var(--primary-color);
            padding: 16px;
            text-align: left;
            margin-bottom: 24px;
            border-radius: 0 8px 8px 0;
        }

        .reason-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
            display: block;
            font-weight: 600;
        }

        .reason-text {
            color: #374151;
            font-size: 15px;
            line-height: 1.5;
        }

        .contact-info {
            text-align: left;
            margin-bottom: 32px;
            padding: 16px;
            border: 1px dashed #d1d5db;
            border-radius: 12px;
        }

        .contact-info h4 {
            font-size: 14px;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #4b5563;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .contact-item i {
            color: var(--primary-color);
            font-size: 16px;
        }

        .contact-item strong {
            color: #1f2937;
        }

        .btn-ok {
            width: 100%;
            padding: 14px;
            background: var(--text-dark);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-ok:hover {
            background: var(--primary-color);
        }

        .logout-form {
            display: none;
        }
    </style>
</head>
<body>
    <div class="modal-container">
        <div class="icon-wrapper">
            <i class="ri-user-forbid-line"></i>
        </div>
        
        <h2>Account Restricted</h2>
        
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 24px;">
            Your account has been blacklisted from using the Hasta Car Rental system.
        </p>

        <div class="reason-box">
            <span class="reason-label">Reason for Restriction</span>
            <p class="reason-text">
                {{ auth()->user()->blacklist_reason ?: 'No specific reason provided. Please contact support for details.' }}
            </p>
        </div>

        <div class="contact-info">
            <h4>Please contact us to resolve this issue:</h4>
            <div class="contact-item">
                <i class="ri-phone-fill"></i>
                <span>Phone: <strong>+60 11-1090 0700</strong></span>
            </div>
            <div class="contact-item">
                <i class="ri-mail-fill"></i>
                <span>Email: <strong>hastatraveltours@gmail.com</strong></span>
            </div>
        </div>

        <button onclick="document.getElementById('logout-form').submit();" class="btn-ok">
            OK
        </button>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
        </form>
    </div>
</body>
</html>
