<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
      #floating-alert {
          position: fixed;
          top: 20px;
          left: 50%;
          transform: translateX(-50%);
          z-index: 9999;
          padding: 0.8rem 1.5rem;
          border-radius: 10px;
          box-shadow: 0 10px 25px rgba(0,0,0,0.1);
          font-weight: 600;
          display: flex;
          align-items: center;
          gap: 10px;
          transition: all 0.3s ease;
          opacity: 0;
          visibility: hidden;
      }
      #floating-alert.show {
          opacity: 1;
          visibility: visible;
          top: 100px;
      }
      #floating-alert.error { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
      #floating-alert.success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }

      @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Syncopate:wght@400;700&display=swap");

      :root {
        --primary-color: #ec5a29; /* Theme Color */
        --primary-color-dark: #d14a1e;
        --text-dark: #15191d;
        --text-light: #737373;
        --extra-light: #e5e5e5;
        --white: #ffffff;
        --max-width: 1200px;
        --header-font: "Syncopate", sans-serif;
      }

      * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
      }

      .section__container {
        max-width: var(--max-width);
        margin: auto;
        padding: 2rem 1rem;
      }

      /* Centered Section Headers */
      .section__header {
        font-size: 3.25rem;
        font-weight: 700;
        font-family: var(--header-font);
        color: var(--text-dark);
        letter-spacing: -5px;
        line-height: 4.25rem;
        text-align: center;
        margin-bottom: 4rem;
      }

      /* Base Button Style */
      .btn {
        padding: 1rem 1.5rem;
        outline: none;
        border: none;
        font-size: 1rem;
        color: var(--white);
        background-color: var(--text-dark);
        border-radius: 10px;
        transition: 0.3s;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
      }

      .btn:hover {
        color: var(--white);
        background-color: var(--primary-color);
      }

      /* Specific Button Styles (Moved from inline HTML) */
      .btn-transparent {
        background: transparent;
        color: var(--white);
        border: 1px solid var(--white);
      }

      .btn-primary {
        background-color: var(--primary-color);
        color: var(--white);
      }

      img {
        display: block;
        width: 100%;
      }

      a {
        text-decoration: none;
        transition: 0.3s;
      }

      ul {
        list-style: none;
      }

      html,
      body {
        scroll-behavior: smooth;
      }

      body {
        font-family: "Poppins", sans-serif;
      }

      /* --- HEADER & BACKGROUND --- */
      header {
        position: relative;
        background-image: linear-gradient(
            rgba(0, 0, 0, 0.6),
            rgba(0, 0, 0, 0.4)
          ),
          url("{{ asset('images/hastabackground.png') }}");
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
        min-height: 100vh;
      }

      /* --- NAVIGATION --- */
      nav {
        position: fixed;
        isolation: isolate;
        top: 0;
        width: 100%;
        z-index: 1000;
        transition: 0.4s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
      }

      nav.nav__fixed {
        background-color: #2d3748;
        padding: 1rem 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
      }

      .nav__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
      }

      .nav__logo img {
        width: 120px;
        height: auto;
      }

      .nav__menu__btn {
        font-size: 1.8rem;
        color: var(--white);
        cursor: pointer;
        display: none;
      }

      .nav__links {
        display: flex;
        align-items: center;
        gap: 2rem;
      }

      .nav__links a {
        font-weight: 500;
        color: var(--white);
      }

      .nav__links a:hover {
        color: var(--primary-color);
      }

      .nav__btn {
        display: flex;
        align-items: center;
        gap: 1rem;
      }

      .notif-wrapper {
        position: relative;
        margin-right: 5px;
      }

      .notif-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--white);
        font-size: 1.5rem;
        position: relative;
        display: flex;
        align-items: center;
        transition: 0.3s;
      }
      
      .notif-btn:hover {
        color: var(--primary-color);
      }

      .notif-badge {
        position: absolute;
        top: 0;
        right: 0;
        width: 10px;
        height: 10px;
        background-color: #ef4444;
        border-radius: 50%;
        border: 2px solid var(--text-dark);
      }

      nav.nav__fixed .notif-badge {
        border-color: var(--text-dark);
      }
      
      .notif-badge {
         border-color: rgba(0,0,0,0.5);
      }

      .notif-dropdown {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 15px;
        width: 320px;
        background-color: var(--white);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        overflow: hidden;
        z-index: 1001;
        text-align: left;
        color: var(--text-dark);
      }

      .notif-header {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
        font-size: 0.9rem;
        background: #f9f9f9;
      }

      .notif-item {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: start;
        gap: 10px;
        transition: 0.2s;
        text-decoration: none;
        color: inherit;
      }

      .notif-item:hover {
        background-color: #f5f5f5;
      }

      .notif-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
      }
      .notif-icon.success { background: #dcfce7; color: #16a34a; }
      .notif-icon.error { background: #fee2e2; color: #dc2626; }
      .notif-icon.info { background: #dbeafe; color: #2563eb; }

      .notif-content p {
        font-size: 0.85rem;
        font-weight: 500;
        line-height: 1.4;
        margin-bottom: 4px;
      }

      .notif-content span {
        font-size: 0.7rem;
        color: var(--text-light);
      }

      .notif-footer {
        padding: 12px;
        text-align: center;
        background: #f9f9f9;
        border-top: 1px solid #f0f0f0;
      }

      .notif-footer a {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--primary-color);
      }

      /* Desktop only utility class for new links */
      .mobile-only {
        display: none;
      }

      /* --- HERO CONTAINER & FORM --- */
      .header__container {
        position: relative;
        padding: 12rem 1rem 5rem;
        text-align: center;
      }

      .header__container h1 {
        max-width: 850px;
        margin-inline: auto;
        margin-bottom: 2rem;
        font-size: 4.5rem;
        font-weight: 700;
        font-family: var(--header-font);
        color: var(--white);
        letter-spacing: -5px;
        line-height: 5rem;
      }

      .header__container form {
        max-width: 900px;
        margin-inline: auto;
        margin-bottom: 4rem;
        padding: 2rem;
        background-color: var(--white);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        border-radius: 12px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        text-align: left;
      }

      .input__group {
        display: flex;
        flex-direction: column;
        gap: 8px;
      }

      .input__group label {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-dark);
      }

      .input__group input {
        width: 100%;
        padding: 10px;
        border: 1px solid var(--extra-light);
        border-radius: 5px;
        font-size: 1rem;
        color: var(--text-light);
        outline: none;
      }

      /* Helper for Time Inputs */
      .date-time-wrapper {
        display: flex;
        gap: 5px;
      }
      .date-time-wrapper input[type="date"] {
        flex: 2;
      }
      .date-time-wrapper input[type="time"] {
        flex: 1;
      }

      .checkbox_group {
        grid-column: 1 / span 2;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
      }

      .dropoff_group {
        grid-column: 1 / span 2;
        display: none; /* Controlled by JS */
        flex-direction: column;
        gap: 8px;
        margin-top: 10px;
      }

      .header__container form .btn {
        grid-column: 3;
        justify-self: end;
        align-self: end;
        background-color: var(--primary-color);
        padding: 12px 25px;
        font-size: 1.5rem;
      }

      .header__container img {
        max-width: 900px;
        margin-inline: auto;
        filter: drop-shadow(5px 20px 20px rgba(0, 0, 0, 0.5));
      }

      .scroll__down {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        font-size: 1.5rem;
        padding: 10px;
        color: var(--white);
        background-color: var(--primary-color);
        border-radius: 50%;
        border: 4px solid var(--white);
        z-index: 2;
      }

      /* --- RANGE SECTION (CENTERED LAYOUT) --- */
      .range__container {
        scroll-margin-top: 100px; /* Offset for fixed navbar */
      }

      .range__grid {
        display: flex; /* Flex allows centering items in the last row */
        flex-wrap: wrap;
        justify-content: center; /* Centers the cards horizontally */
        gap: 2rem;
      }

      .range__card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid #f0f0f0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease;
      }

      .range__card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
      }

      .range__card img {
        width: 100%;
        height: 220px;
        object-fit: contain;
        background-color: #ffffff;
        padding: 20px;
        border-bottom: 1px solid #f5f5f5;
      }

      .range__details {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
      }

      .card__title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #15191d;
        margin: 0;
      }

      .card__price {
        font-size: 0.9rem;
        color: #737373;
        margin: 0;
      }

      .card__price strong {
        color: #ec5a29;
        font-size: 1.1rem;
      }

      .card__divider {
        border: none;
        border-top: 1px solid #eee;
        margin: 5px 0;
      }

      .card__tags {
        display: flex;
        gap: 8px;
      }

      .card__tag {
        background-color: #f4f4f4;
        color: #555;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
      }

      .card__location {
        font-size: 0.85rem;
        color: #999;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: auto;
      }

      .card__btn {
        width: 100%;
        padding: 10px;
        background-color: #ec5a29;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 15px;
        transition: background 0.3s;
      }

      .card__btn:hover {
        background-color: #d14a1e;
      }

      .range__card.unavailable {
        background-color: #f5f5f5;
        opacity: 0.6;
        pointer-events: none;
        cursor: not-allowed;
        filter: grayscale(100%);
      }

      .range__card.unavailable .card__btn {
        background-color: #999;
      }

      /* --- FOOTER --- */
      footer {
        background-color: var(--text-dark);
        padding-top: 5rem;
      }

      .footer__container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        border-bottom: 1px solid var(--text-light);
        padding-bottom: 2rem;
      }

      .footer__col h4 {
        margin-bottom: 2rem;
        font-size: 1.2rem;
        font-family: var(--header-font);
        color: var(--white);
      }

      .footer__links a {
        color: var(--text-light);
        display: block;
        margin-bottom: 1rem;
      }
      .footer__links a:hover {
        color: var(--primary-color);
      }

      .footer__socials {
        display: flex;
        gap: 1rem;
      }
      .footer__socials a {
        padding: 8px 12px;
        font-size: 1.25rem;
        color: var(--text-light);
        border: 2px solid var(--text-light);
        border-radius: 100%;
      }

      .footer__socials a:hover {
        color: var(--primary-color);
        border-color: var(--primary-color);
      }

      /* --- MEDIA QUERIES --- */
      @media (max-width: 1024px) {
        .range__card {
          flex: 0 1 calc(50% - 2rem); /* 2 cards per row on tablet */
        }
      }

      /* Consolidated Mobile Styles */
      @media (max-width: 768px) {
        /* Nav Layout */
        .nav__menu__btn {
          display: block;
        }
        .nav__links {
          position: absolute;
          top: 100%;
          left: 0;
          width: 100%;
          background-color: var(--primary-color);
          flex-direction: column;
          padding: 2rem;
          transform: translateY(-150%);
          transition: 0.5s;
          z-index: 9;
        }
        .nav__links.open {
          transform: translateY(0);
        }
        .nav__btn {
          display: none;
        }

        /* Hero Layout */
        .header__container h1 {
          font-size: 2.5rem;
        }
        .header__container form {
          grid-template-columns: 1fr;
        }
        .header__container form .btn {
          grid-column: 1;
        }
        .checkbox_group,
        .dropoff_group {
          grid-column: 1;
        }

        /* Range Section */
        .range__card {
          flex: 0 1 100%; /* 1 card per row on mobile */
        }

        /* Footer */
        .footer__container {
          grid-template-columns: repeat(2, 1fr);
        }

        /* Mobile Login/Register Links */
        .mobile-only {
          display: block;
          width: 100%;
        }

        .mobile-only a {
          display: block;
          text-align: center;
          border: 1px solid var(--white);
          border-radius: 5px;
          margin-top: 10px;
          padding: 10px;
          background-color: transparent;
        }

        .mobile-only a:hover {
          background-color: var(--white);
          color: var(--primary-color);
        }
      }

      /* Booking Page Specific Styles */
      .booking__grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        align-items: flex-start;
      }

      .booking__form {
        background-color: var(--white);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      }

      .booking__summary {
        background-color: #f9f9f9;
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid var(--extra-light);
        position: sticky;
        top: 100px;
      }

      .form__section {
        margin-bottom: 2rem;
      }

      .form__section h3 {
        border-bottom: 2px solid var(--primary-color);
        display: inline-block;
        margin-bottom: 1.5rem;
        font-size: 1.2rem;
      }

      /* Custom Radio Circle for CSS-only toggle */
      .radio-circle {
        width: 20px;
        height: 20px;
        border: 2px solid #ccc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      /* Checked State */
      input[type="radio"]:checked + .payment-label-content .radio-circle {
        border-color: var(--primary-color);
      }
      input[type="radio"]:checked
        + .payment-label-content
        .radio-circle::after {
        content: "";
        width: 10px;
        height: 10px;
        background-color: var(--primary-color);
        border-radius: 50%;
        display: block;
      }

      /* =========================================
   MOBILE RESPONSIVE STYLES (The Fix)
   ========================================= */
      @media (max-width: 992px) {
        /* 1. Stack the layout */
        .booking__grid {
          grid-template-columns: 1fr !important;
        }

        /* 2. Style the Summary Box to be visible */
        .booking__summary {
          position: static !important;
          margin-top: 2rem !important;

          /* Colors and Borders */
          background-color: #fff8f5 !important;
          border: 2px solid #ec5a29 !important;
          box-shadow: 0 8px 20px rgba(236, 90, 41, 0.15) !important;

          /* Ensure visibility */
          z-index: 100 !important;
          display: block !important;
          width: 100% !important;
          padding: 1.5rem !important;
          border-radius: 12px !important;
        }

        /* 3. Make the total price text larger on mobile */
        .booking__summary strong {
          font-size: 1.4rem !important;
        }
      }

      /* Refund Box Styling */
      .refund-box {
        background-color: #fcfcfc;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 1.5rem;
        transition: 0.3s;
      }
      .refund-box:hover {
        border-color: var(--primary-color);
        box-shadow: 0 5px 15px rgba(236, 90, 41, 0.05);
      }
      
      .input-with-icon {
        position: relative;
      }
      .input-with-icon i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-color);
        font-size: 1.1rem;
      }
      .input-with-icon input {
        padding-left: 45px !important; 
      }

      /* Ensure typed form text is dark for readability */
      input[type="text"],
      input[type="tel"],
      input[type="datetime-local"],
      input[type="date"],
      input[type="time"],
      input[type="file"],
      input[type="search"],
      select,
      textarea {
        color: #111 !important;
      }

      /* keep placeholders light */
      input::placeholder,
      textarea::placeholder {
        color: #999 !important;
      }

      /* --- AUTOFILL / READONLY STYLES --- */
      .input-autofill[readonly] {
          background-color: #f3f4f6 !important; /* Gray background */
          border-color: #e5e7eb !important;
          color: #6b7280 !important; /* Gray text */
          cursor: not-allowed;
      }

      .btn-edit {
          background: none;
          border: 1px solid #ddd;
          border-radius: 4px;
          padding: 5px 10px;
          font-size: 0.85rem;
          color: #555;
          cursor: pointer;
          transition: 0.2s;
          display: flex;
          align-items: center;
          gap: 5px;
      }
      .btn-edit:hover {
          background-color: #f0f0f0;
          color: var(--primary-color);
          border-color: var(--primary-color);
      }

      .autofill-hint {
          font-size: 0.85rem;
          color: var(--primary-color);
          font-weight: 500;
          display: flex;
          align-items: center;
          gap: 5px;
      }
      
      .autofill-notice {
          font-size: 0.85rem;
          background-color: #fff7ed; /* Light Orange */
          color: #c2410c; /* Dark Orange/Red */
          padding: 8px 12px;
          border-radius: 6px;
          margin-bottom: 15px;
          border: 1px solid #ffedd5;
          display: flex;
          gap: 8px;
          align-items: center;
      }
      
      @media (max-width: 600px) {
          .mobile-only-hide {
              display: none;
          }
      }
    </style>
  </head>

  <body>
    <!-- Floating Alert Element -->
    <div id="floating-alert">
        <i id="alert-icon"></i>
        <span id="alert-message"></span>
    </div>

    <header>
      <nav id="navbar">
        <div class="nav__header">
          <div class="nav__logo">
            <a href="{{ url('/#home') }}">
              <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta" />
            </a>
          </div>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-line"></i>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="{{ url('/#home') }}">Dashboard</a></li>
          <li><a href="{{ url('/#vehicles') }}">Vehicles</a></li>
          <li><a href="{{ url('/#contact') }}">Contact</a></li>
          <li><a href="{{ url('/#about') }}">About Us</a></li>
          <li><a href="{{ route('profile.edit') }}">My Profile</a></li>

          <li class="mobile-only">
            <a href="{{ route('profile.edit') }}">Profile</a>
          </li>

          <li class="mobile-only">
            <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button
                type="submit"
                style="background:none; border:none; padding:0; color:inherit; font:inherit; cursor:pointer;"
              >
                Logout
              </button>
            </form>
          </li>
        </ul>

        <div class="nav__btn">
          @guest
            <a href="{{ route('login') }}" class="btn btn-transparent">
              <strong>Login</strong>
            </a>
            <a href="{{ route('register') }}" class="btn btn-primary">
              <strong>Register</strong>
            </a>
          @else
            {{-- User is logged in --}}
            <div x-data="{ open: false }" class="notif-wrapper">
              <button @click="open = !open" @click.away="open = false" class="notif-btn">
                 <i class="ri-notification-3-line"></i>
                 @if(Auth::user()->unreadNotifications->count() > 0)
                     <span class="notif-badge"></span>
                 @endif
              </button>
 
              <div x-show="open" style="display: none;" class="notif-dropdown">
                 <div class="notif-header">
                     <span>Notifications</span>
                     @if(Auth::user()->unreadNotifications->count() > 0)
                         <span style="font-size: 0.7rem; background: #fee2e2; color: #dc2626; padding: 2px 6px; border-radius: 10px;">{{ Auth::user()->unreadNotifications->count() }} New</span>
                     @endif
                 </div>
 
                 <div style="max-height: 300px; overflow-y: auto;">
                     @forelse(Auth::user()->notifications->take(3) as $notification)
                         <div class="notif-item">
                             <div class="notif-icon {{ isset($notification->data['status']) && $notification->data['status'] == 'Approved' ? 'success' : (isset($notification->data['status']) && $notification->data['status'] == 'Rejected' ? 'error' : 'info') }}">
                                 @if(isset($notification->data['status']) && $notification->data['status'] == 'Approved')
                                     <i class="ri-check-line"></i>
                                 @elseif(isset($notification->data['status']) && $notification->data['status'] == 'Rejected')
                                     <i class="ri-close-line"></i>
                                 @else
                                     <i class="ri-notification-line"></i>
                                 @endif
                             </div>
                             <div class="notif-content">
                                 <p>{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                 <span>{{ $notification->created_at->diffForHumans() }}</span>
                             </div>
                         </div>
                     @empty
                         <div style="padding: 20px; text-align: center; color: #999; font-size: 0.85rem;">
                             No notifications
                         </div>
                     @endforelse
                 </div>
 
                 <div class="notif-footer">
                     <a href="{{ route('profile.edit', ['tab' => 'notifications']) }}">View All Notifications</a>
                 </div>
              </div>
           </div>


            <div x-data="{ userOpen: false }" style="position: relative;">
                <button @click="userOpen = !userOpen" @click.away="userOpen = false" style="display: flex; align-items: center; gap: 0.5rem; background: none; border: none; cursor: pointer; outline: none;" class="group">
                    <img style="height: 36px; width: 36px; border-radius: 50%; object-fit: cover; border: 2px solid transparent; transition: border-color 0.3s;" 
                         onmouseover="this.style.borderColor='#ec5a29'" 
                         onmouseout="this.style.borderColor='transparent'"
                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ec5a29&color=fff" 
                         alt="Profile">
                    <span style="color: white; font-size: 0.875rem; font-weight: 500; transition: color 0.3s;" 
                          onmouseover="this.style.color='#ec5a29'" 
                          onmouseout="this.style.color='white'"
                          class="hidden md:block">{{ Auth::user()->name }}</span>
                    <i class="ri-arrow-down-s-line" style="color: white; transition: color 0.3s;"
                       onmouseover="this.style.color='#ec5a29'" 
                       onmouseout="this.style.color='white'"></i>
                </button>

                <div x-show="userOpen" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 12px; width: 12rem; background-color: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; z-index: 50; border: 1px solid #f3f4f6; padding: 4px 0;"
                     x-transition>
                    
                    <div style="padding: 12px 16px; border-bottom: 1px solid #f9fafb;">
                        <p style="font-size: 0.75rem; color: #6b7280; margin-bottom: 2px;">Signed in as</p>
                        <p style="font-size: 0.875rem; font-weight: 700; color: #1f2937; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->email }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" style="display: block; padding: 8px 16px; font-size: 0.875rem; color: #374151; transition: background-color 0.2s; text-decoration: none;"
                       onmouseover="this.style.backgroundColor='#f9fafb'; this.style.color='#ec5a29'"
                       onmouseout="this.style.backgroundColor='transparent'; this.style.color='#374151'">
                        <i class="ri-user-line" style="margin-right: 8px; vertical-align: middle;"></i> <strong>My Profile</strong>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; text-align: left; padding: 8px 16px; font-size: 0.875rem; color: #dc2626; background: none; border: none; font-weight: 500; cursor: pointer; transition: background-color 0.2s;"
                                onmouseover="this.style.backgroundColor='#fef2f2'"
                                onmouseout="this.style.backgroundColor='transparent'">
                            <i class="ri-logout-box-r-line" style="margin-right: 8px; vertical-align: middle;"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
          @endguest
        </div>
      </nav>
      <section class="section__container">
        <br /><br /><br /><br /><br />
        <h2
          class="section__header"
          style="text-align: left; margin-bottom: 2rem; color: #ffb273ff"
        >
          Complete Your Booking
        </h2>

        {{-- Session Timer --}}
        <div id="session-timer" style="
            position: sticky; 
            top: 80px; 
            z-index: 99; 
            background: #fff; 
            border: 2px solid #f4f6fa; 
            padding: 1rem; 
            border-radius: 10px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 600;
            color: #d12e34;
        ">
            Time Remaining to Complete Booking: <span id="timer-display" style="font-size: 1.2rem;">10:00</span>
        </div>

        <div class="booking__grid">
          <div class="booking__form">
            <form
            id="bookingForm"
            action="{{ route('booking.store') }}"
            method="POST"
            enctype="multipart/form-data"
          >
          @csrf
          <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

              <div class="form__section">
                <h3><i class="ri-calendar-check-line"></i> Rental Period</h3>
                <div
                  style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;"
                >
                  <div class="input__group">
                    <label>Start Date</label>
                    {{-- 🔥 修复重点：如果 URL 里有 start_date 和 start_time，自动合并成 'YYYY-MM-DDTHH:MM' 填入 --}}
                    <div class="date-time-wrapper">
                        <input type="date" id="start_date_visual" required value="{{ request('start_date') }}" min="{{ date('Y-m-d') }}" />
                        <select id="start_time_visual" required style="padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px;">
                            <option value="" disabled {{ !request('start_time') ? 'selected' : '' }}>Select Time</option>
                            @for($i=420; $i<1440; $i+=10)
                                @php $t = sprintf('%02d:%02d', floor($i/60), $i%60); @endphp
                                <option value="{{ $t }}" {{ request('start_time') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                        <input type="hidden" name="start_time" id="start_time_hidden" value="{{ request('start_date') && request('start_time') ? request('start_date').'T'.request('start_time') : '' }}">
                    </div>
                  </div>
                  <div class="input__group">
                    <label>End Date</label>
                    {{-- 🔥 修复重点：同上，自动合并 stop_date 和 stop_time --}}
                    <div class="date-time-wrapper">
                        <input type="date" id="end_date_visual" required value="{{ request('stop_date') }}" min="{{ date('Y-m-d') }}" />
                        <select id="end_time_visual" required style="padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px;">
                            <option value="" disabled {{ !request('end_time') ? 'selected' : '' }}>Select Time</option>
                            @for($i=420; $i<1440; $i+=60)
                                @php $t = sprintf('%02d:%02d', floor($i/60), $i%60); @endphp
                                <option value="{{ $t }}" {{ request('stop_time') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                        <input type="hidden" name="end_time" id="end_time_hidden" value="{{ request('stop_date') && request('stop_time') ? request('stop_date').'T'.request('stop_time') : '' }}">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form__section">
                <h3><i class="ri-map-pin-line"></i> Pickup & Dropoff</h3>
                <p
                  style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 1rem;"
                >
                  Additional fees apply for delivery outside Student Mall.
                </p>

                <div
                  style="display: grid; grid-template-columns: 1fr; gap: 1rem;"
                >
                  <div class="input__group">
                    <label>Pickup Location</label>
                    <select
                      name="pickup_location"
                      id="pickup_location"
                      onchange="handlePickupChange()"
                      required
                      style="width: 100%; padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px;"
                    >
                      <option
                        value="Student Mall"
                        {{ $pickupLoc == 'Student Mall' ? 'selected' : '' }}
                        >Student Mall (Free)</option
                      >
                      <option
                        value="campus"
                        {{ $pickupLoc == 'campus' ? 'selected' : '' }}
                        >In Campus (+RM 2.50)</option
                      >
                      <option
                        value="taman_u"
                        {{ $pickupLoc == 'taman_u' ? 'selected' : '' }}
                        >Taman Universiti (+RM 7.50)</option
                      >
                      <option
                        value="jb"
                        {{ $pickupLoc == 'jb' ? 'selected' : '' }}
                        >Other Area JB (+RM 25)</option
                      >
                    </select>
                  </div>

                  <div
                    class="input__group"
                    id="custom_pickup_group"
                    style="display: none;"
                  >
                    <label>Specific Pickup Address</label>
                    <input
                      type="text"
                      name="custom_pickup_address"
                      id="custom_pickup_address"
                      placeholder="Enter your specific pickup address"
                      style="width: 100%; padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px;"
                    />
                  </div>

                  <div
                    style="display: flex; align-items: center; gap: 10px; margin: 1rem 0;"
                  >
                    <input
                      type="checkbox"
                      id="same_location_checkbox"
                      checked
                      onchange="handleSameLocationChange()"
                    />
                    <label
                      for="same_location_checkbox"
                      style="margin: 0; cursor: pointer;"
                      >Drop off location same as pick up location</label
                    >
                  </div>

                  <input
                    type="hidden"
                    name="dropoff_location"
                    id="dropoff_location_hidden"
                    value="Student Mall"
                  />

                  <div id="dropoff_section" style="display: none;">
                    <div class="input__group">
                      <label>Dropoff Location</label>
                      <select
                        name="dropoff_location"
                        id="dropoff_location"
                        onchange="handleDropoffChange()"
                        style="width: 100%; padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px;"
                      >
                        <option value="">-- Select Dropoff Location --</option>
                        <option
                          value="Student Mall"
                          {{ $dropoffLoc == 'Student Mall' ? 'selected' : '' }}
                          >Student Mall (Free)</option
                        >
                        <option
                          value="campus"
                          {{ $dropoffLoc == 'campus' ? 'selected' : '' }}
                          >In Campus (+RM 2.50)</option
                        >
                        <option
                          value="taman_u"
                          {{ $dropoffLoc == 'taman_u' ? 'selected' : '' }}
                          >Taman Universiti (+RM 7.50)</option
                        >
                        <option
                          value="jb"
                          {{ $dropoffLoc == 'jb' ? 'selected' : '' }}
                          >Other Area JB (+RM 25)</option
                        >
                      </select>
                    </div>

                    <div
                      class="input__group"
                      id="custom_dropoff_group"
                      style="display: none;"
                    >
                      <label>Specific Dropoff Address</label>
                      <input
                        type="text"
                        name="custom_dropoff_address"
                        id="custom_dropoff_address"
                        placeholder="Enter your specific dropoff address"
                        style="width: 100%; padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px;"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="form__section">
                <h3><i class="ri-ticket-line"></i> Vouchers</h3>
                <div style="margin-bottom: 1.5rem;">
                    <small style="color: #dc2626; display: flex; align-items: center; gap: 5px; font-weight: 700; font-size: 0.8rem; margin-bottom: 0.5rem; background: #fef2f2; padding: 6px 12px; border-radius: 6px; width: fit-content; border: 1px solid #fee2e2;">
                        <i class="ri-information-fill"></i> Apply voucher BEFORE uploading receipt/license.
                    </small>
                    <p style="font-size: 0.85rem; color: var(--text-light);">
                      Select a claimed voucher OR enter a code.
                    </p>
                </div>

                <div
                  style="display: grid; grid-template-columns: 1fr; gap: 1rem;"
                >
                  <div class="input__group">
                    <label>My Rewards</label>
                    <select
                      name="selected_voucher_id"
                      onchange="calculatePrice()"
                      style="width: 100%; padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px;"
                    >
                      <option value="">-- Select a Reward --</option>
                      @foreach($myVouchers as $v)
                      <option
                        value="{{ $v->id }}"
                        {{ request('selected_voucher_id') == $v->id ? 'selected' : '' }}
                      >
                        {{ $v->voucher->name }} ({{ $v->voucher->label }})
                      </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="input__group">
                    <label>Or Enter Promo Code</label>
                    <div style="display: flex; gap: 8px;">
                        <input
                          type="text"
                          name="manual_code"
                          id="manual_code_input"
                          value="{{ request('manual_code') }}"
                          placeholder="e.g. HASTA2024"
                          style="text-transform: uppercase; flex: 1;"
                        />
                        <button type="button" onclick="calculatePrice()" class="btn btn-primary" style="padding: 0 15px; font-size: 0.85rem; height: 42px;">
                            Redeem
                        </button>
                    </div>
                  </div>
                  <div id="voucher-success-label" style="display: none; margin-top: 10px;">
                      <span style="background: #f0fdf4; color: #16a34a; padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; border: 1px solid #dcfce7; display: inline-flex; align-items: center; gap: 5px;">
                          <i class="ri-checkbox-circle-fill"></i> Applied: <span id="applied-voucher-name"></span>
                      </span>
                  </div>
                </div>

              </div>

              <div class="form__section">
                <h3><i class="ri-user-line"></i> <strong>My Profile</strong></h3>
                <div
                  style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;"
                >
                  <div class="input__group">
                    <label>Full Name</label>
                    <input
                      type="text"
                      name="name"
                      value="{{ Auth::user()->name }}"
                      required
                    />
                  </div>
                  <div class="input__group">
                    <label>Phone Number</label>
                    <input
                      type="tel"
                      name="phone"
                      value="{{ Auth::user()->phone_number }}"
                      placeholder="+60..."
                      required
                    />
                  </div>
                </div>
              </div>

              <div class="form__section" id="emergency-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3><i class="ri-alarm-warning-line"></i> Emergency Contact</h3>
                    @if($lastBooking)
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span class="autofill-hint mobile-only-hide"><i class="ri-sparkling-fill"></i> Autofilled from last booking</span>
                            <button type="button" class="btn-edit" onclick="toggleEdit('emergency-section')">
                                <i class="ri-pencil-line"></i> Edit
                            </button>
                        </div>
                    @endif
                </div>
                
                @if($lastBooking)
                    <div class="autofill-notice">
                        <i class="ri-information-line"></i> These details are from your last booking. Click <strong>Edit</strong> to change them.
                    </div>
                @endif

                <div
                  style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;"
                >
                  <div class="input__group">
                    <label>Contact Name</label>
                    <input
                      type="text"
                      name="emergency_name"
                      class="input-autofill"
                      value="{{ old('emergency_name', $lastBooking->emergency_contact_name ?? Auth::user()->emergency_name) }}"
                      required
                      {{ $lastBooking ? 'readonly' : '' }}
                    />
                  </div>
                  <div class="input__group">
                    <label>Contact Number</label>
                    <input
                      type="tel"
                      name="emergency_contact"
                      class="input-autofill"
                      value="{{ old('emergency_contact', $lastBooking->emergency_contact_phone ?? Auth::user()->emergency_contact) }}"
                      required
                      {{ $lastBooking ? 'readonly' : '' }}
                    />
                  </div>
                </div>
                <div class="input__group" style="margin-top: 1rem;">
                  <label>Relationship</label>
                  <input
                    type="text"
                    name="emergency_relationship"
                    class="input-autofill"
                    value="{{ old('emergency_relationship', $lastBooking->emergency_relationship ?? Auth::user()->emergency_relationship) }}"
                    required
                    {{ $lastBooking ? 'readonly' : '' }}
                  />
                </div>
                </div>

              <div class="form__section">
                <h3><i class="ri-secure-payment-line"></i> Payment & Refund</h3>
                
                <div class="refund-box" style="margin-bottom: 2rem;" id="refund-section">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h4 style="font-size: 1rem; margin-bottom: 0; color: var(--primary-color);">
                            Refund Details
                        </h4>
                        @if($lastBooking)
                             <div style="display: flex; align-items: center; gap: 10px;">
                                <span class="autofill-hint mobile-only-hide" style="font-size: 0.8rem;"><i class="ri-sparkling-fill"></i> Autofilled</span>
                                <button type="button" class="btn-edit" onclick="toggleEdit('refund-section')">
                                    <i class="ri-pencil-line"></i> Edit
                                </button>
                            </div>
                        @endif
                    </div>

                     @if($lastBooking)
                        <div class="autofill-notice" style="margin-bottom: 1rem;">
                            <i class="ri-information-line"></i> Check your bank details carefully. Click <strong>Edit</strong> if you changed banks.
                        </div>
                    @endif

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="input__group">
                            <label>Bank Name</label>
                            <div class="input-with-icon">
                                <i class="ri-bank-line"></i>
                                <input type="text" name="refund_bank_name" class="input-autofill" value="{{ old('refund_bank_name', $lastBooking->refund_bank_name ?? Auth::user()->bank_name ?? '') }}" placeholder="e.g. Maybank" required {{ $lastBooking ? 'readonly' : '' }} />
                            </div>
                        </div>
                        <div class="input__group">
                            <label>Account Number</label>
                             <div class="input-with-icon">
                                <i class="ri-hashtag"></i>
                                <input type="text" name="refund_account_number" class="input-autofill" value="{{ old('refund_account_number', $lastBooking->refund_account_number ?? Auth::user()->account_number ?? '') }}" placeholder="e.g. 1122334455" required {{ $lastBooking ? 'readonly' : '' }} />
                            </div>
                        </div>
                        <div class="input__group" style="grid-column: 1 / -1;">
                            <label>Recipient Name</label>
                             <div class="input-with-icon">
                                <i class="ri-user-star-line"></i>
                                <input type="text" name="refund_recipient_name" class="input-autofill" value="{{ old('refund_recipient_name', $lastBooking->refund_recipient_name ?? Auth::user()->account_holder ?? '') }}" placeholder="Name as per bank account" required {{ $lastBooking ? 'readonly' : '' }} />
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px dashed #ccc; margin-bottom: 2rem;">

                <h4 style="font-size: 1rem; margin-bottom: 1rem; color: var(--primary-color);">Make Payment</h4>
                <div class="payment-card">
                  <input
                    type="radio"
                    name="payment_method"
                    id="pay-qr"
                    value="qr_pay"
                    checked
                    style="display: none;"
                  />
                  <label for="pay-qr" class="payment-label-content">
                    <div
                      style="display: flex; align-items: center; gap: 15px;"
                    >
                      <div class="radio-circle"></div>
                      <div style="flex: 1;"><strong>DuitNow QR</strong></div>
                      <i
                        class="ri-qr-code-line"
                        style="font-size: 1.5rem; color: var(--primary-color);"
                      ></i>
                    </div>
                    <div
                      class="payment-details-box"
                      style="text-align: center;"
                    >
                      <img
                        src="{{ asset('images/paymentqr.png') }}"
                        alt="QR"
                        style="max-width: 200px;"
                      />
                    </div>
                  </label>
                </div>

                <div class="payment-card">
                  <input
                    type="radio"
                    name="payment_method"
                    id="pay-bank"
                    value="bank_transfer"
                    style="display: none;"
                  />
                  <label for="pay-bank" class="payment-label-content">
                    <div
                      style="display: flex; align-items: center; gap: 15px;"
                    >
                      <div class="radio-circle"></div>
                      <div style="flex: 1;">
                        <strong>Manual Transfer</strong>
                      </div>
                      <i
                        class="ri-bank-line"
                        style="font-size: 1.5rem; color: var(--primary-color);"
                      ></i>
                    </div>
                    <div class="payment-details-box">
                      <div
                        style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid var(--primary-color);"
                      >
                        <p><strong>CIMB Bank</strong> | Hasta Car Rental</p>
                        <p
                          style="font-size: 1.1rem; color: var(--primary-color); font-weight: 700;"
                        >
                          8600123456
                        </p>
                      </div>
                    </div>
                  </label>
                </div>

                <div class="input__group" style="margin-top: 1rem;">
                  <label>Upload Payment Receipt</label>
                  <input
                    type="file"
                    name="receipt_image"
                    required
                    accept="image/*, .pdf"
                  />
                </div>
              </div>



              {{-- SESSION ALERTS --}}
              @if (session('success'))
              <div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
                  <i class="ri-checkbox-circle-line"></i> {{ session('success') }}
              </div>
              @endif

              @if (session('error'))
              <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
                  <i class="ri-error-warning-line"></i> {{ session('error') }}
              </div>
              @endif
              {{-- ERROR DISPLAY BLOCK --}}
              @if ($errors->any())
              <div
                style="background-color: #ffe6e6; border: 1px solid #d93025; color: #d93025; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;"
              >
                <h4 style="margin: 0 0 0.5rem 0; font-weight: bold;">
                  <i class="ri-error-warning-line"></i> Please fix the
                  following:
                </h4>
                <ul style="margin-left: 1.5rem; list-style: disc;">
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif
            </form>
          </div>

          <div class="booking__summary">
            <h3>Booking Summary</h3>
            <img
              src="{{ asset('images/' . $vehicle->vehicle_image) }}"
              alt="Car"
              style="border-radius: 8px; margin-bottom: 1rem; width: 100%; object-fit: cover;"
            />

            <div style="margin-bottom: 1.5rem;">
                <h4 style="font-size: 1.2rem; font-weight: 700; color: #15191d; margin-bottom: 0.2rem;">
                  {{ $vehicle->brand }} {{ $vehicle->model }}
                </h4>
                <p style="font-size: 0.9rem; color: #666;">
                  <strong>Plate:</strong> {{ $vehicle->plate_number }}
                </p>
            </div>

            <div
              style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;"
            >
              <span>Rate</span>
              <div>
                  <strong class="text-primary">RM {{ number_format($vehicle->pricingTier ? $vehicle->pricingTier->rules->min('price') : $vehicle->price_per_hour, 2) }} / hr</strong>
                  @include('partials.price-modal', ['vehicle' => $vehicle])
              </div>
            </div>

            <div
              style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;"
            >
              <span>Duration</span>
              <strong id="summary-hours">{{ $hours ?? 0 }} hours</strong>
            </div>

            <div
              style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;"
            >
              <span>Subtotal</span>
              <strong id="summary-subtotal">RM {{ number_format($subtotal ?? 0, 2) }}</strong>
            </div>

            <div
              style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--primary-color);"
            >
              <span>Delivery Fee</span>
              <strong id="summary-delivery">RM {{ number_format($deliveryFee ?? 0, 2) }}</strong>
            </div>

            <div
              style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #28a745;"
            >
              <span>Discount</span>
              <strong id="summary-discount">- RM {{ number_format($discount ?? 0, 2) }}</strong>
            </div>
            
            <div
              style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;"
            >
              <span>Security Deposit</span>
              <strong id="summary-deposit">RM 0.00</strong>
            </div>

            <hr style="margin: 1rem 0; opacity: 0.2;" />

            <div
              style="display: flex; justify-content: space-between; font-size: 1.4rem; color: var(--primary-color);"
            >
              <span>Total</span>
              <strong id="summary-total">RM {{ number_format($total ?? 0, 2) }}</strong>
            </div>
             <div style="text-align: right; font-size: 0.8rem; color: #666; margin-top: 5px;">
                 <span id="summary-stamps">+ {{ $stamps ?? 0 }} Stamps</span>
             </div>

             <!-- Moved generic submit button here -->
              <button 
                type="submit" 
                form="bookingForm" 
                class="btn btn-primary" 
                style="width: 100%; margin-top: 1.5rem; font-size: 1.2rem; padding: 15px;"
              >
                  Confirm Booking <i class="ri-arrow-right-line"></i>
              </button>
          </div>
        </div>

    </header>

    <script>
        (function() {
            // --- 1. UI HELPERS (Mobile Menu & Navbar) ---
            const menuBtn = document.getElementById("menu-btn");
            const navLinks = document.getElementById("nav-links");
            if (menuBtn && navLinks) {
                const menuBtnIcon = menuBtn.querySelector("i");
                menuBtn.addEventListener("click", () => {
                    navLinks.classList.toggle("open");
                    const isOpen = navLinks.classList.contains("open");
                    if (menuBtnIcon) menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
                });
            }
            const navbar = document.getElementById("navbar");
            if (navbar) {
                window.addEventListener("scroll", () => {
                    if (window.scrollY > 50) navbar.classList.add("nav__fixed");
                    else navbar.classList.remove("nav__fixed");
                });
            }

            // --- 2. LOCATION & SAME-LOCATION LOGIC (Teammate) ---
            window.handlePickupChange = function() {
                const pEl = document.getElementById("pickup_location");
                if(!pEl) return;
                const pickupValue = pEl.value;
                const customGroup = document.getElementById("custom_pickup_group");
                const customInput = document.getElementById("custom_pickup_address");
                if (pickupValue !== "Student Mall") {
                    if(customGroup) customGroup.style.display = "block";
                    if(customInput) customInput.setAttribute("required", "required");
                } else {
                    if(customGroup) customGroup.style.display = "none";
                    if(customInput) {
                        customInput.removeAttribute("required");
                        customInput.value = "";
                    }
                }
                calculatePrice();
            };

            window.handleDropoffChange = function() {
                const dEl = document.getElementById("dropoff_location");
                if(!dEl) return;
                const dropoffValue = dEl.value;
                const dropoffHidden = document.getElementById("dropoff_location_hidden");
                const customGroup = document.getElementById("custom_dropoff_group");
                const customInput = document.getElementById("custom_dropoff_address");
                if(dropoffHidden) dropoffHidden.value = dropoffValue;
                if (dropoffValue !== "Student Mall") {
                    if(customGroup) customGroup.style.display = "block";
                    if(customInput) customInput.setAttribute("required", "required");
                } else {
                    if(customGroup) customGroup.style.display = "none";
                    if(customInput) {
                        customInput.removeAttribute("required");
                        customInput.value = "";
                    }
                }
                calculatePrice();
            };

            window.handleSameLocationChange = function() {
                const checkbox = document.getElementById("same_location_checkbox");
                if(!checkbox) return;
                const dropoffSection = document.getElementById("dropoff_section");
                const dropoffSelect = document.getElementById("dropoff_location");
                const dropoffHidden = document.getElementById("dropoff_location_hidden");
                const pickupEl = document.getElementById("pickup_location");
                const pickupValue = pickupEl ? pickupEl.value : "";

                if (checkbox.checked) {
                    if(dropoffSection) dropoffSection.style.display = "none";
                    if(dropoffSelect) {
                        dropoffSelect.removeAttribute("required");
                        dropoffSelect.removeAttribute("name");
                        dropoffSelect.value = "";
                    }
                    if(dropoffHidden) dropoffHidden.value = pickupValue;
                    const cdGroup = document.getElementById("custom_dropoff_group");
                    if(cdGroup) cdGroup.style.display = "none";
                } else {
                    if(dropoffSection) dropoffSection.style.display = "block";
                    if(dropoffSelect) {
                        dropoffSelect.setAttribute("required", "required");
                        dropoffSelect.setAttribute("name", "dropoff_location");
                    }
                    if(dropoffHidden && dropoffSelect) dropoffHidden.value = dropoffSelect.value;
                }
                calculatePrice();
            };

            // --- 3. PRICE CALCULATION ---
            window.calculatePrice = function() {
                const startH = document.getElementById("start_time_hidden");
                const endH = document.getElementById("end_time_hidden");
                if(!startH || !endH) return;
                const startStr = startH.value;
                const endStr = endH.value;
                
                const vIdEl = document.querySelector('input[name="vehicle_id"]');
                const vehicleId = vIdEl ? vIdEl.value : null;
                
                const pEl = document.getElementById("pickup_location");
                const pickup = pEl ? pEl.value : "";
                
                let dropoff = pickup;
                const sameLocCb = document.getElementById("same_location_checkbox");
                const isSame = sameLocCb ? sameLocCb.checked : true;
                if (!isSame) {
                    const dEl = document.getElementById("dropoff_location");
                    if(dEl) dropoff = dEl.value;
                }

                const voucherSelect = document.querySelector('select[name="selected_voucher_id"]');
                const voucherId = voucherSelect ? voucherSelect.value : null;
                const manualCodeInput = document.querySelector('input[name="manual_code"]');
                const manualCode = manualCodeInput ? manualCodeInput.value : '';

                if (!startStr || !endStr) return;

                fetch('{{ route("booking.calculate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        vehicle_id: vehicleId,
                        start_time: startStr,
                        end_time: endStr,
                        pickup_location: pickup,
                        dropoff_location: dropoff,
                        selected_voucher_id: voucherId,
                        manual_code: manualCode
                    })
                })
                .then(res => res.json())
                .then(data => {
                    const alertBox = document.getElementById('floating-alert');
                    const alertMsg = document.getElementById('alert-message');
                    const alertIcon = document.getElementById('alert-icon');
                    const successLabel = document.getElementById('voucher-success-label');
                    const appliedName = document.getElementById('applied-voucher-name');

                    if(data.error) {
                        alertMsg.innerText = data.error;
                        alertIcon.className = "ri-error-warning-line";
                        alertBox.className = "show error";
                        if(successLabel) successLabel.style.display = 'none';
                        setTimeout(() => alertBox.className = "error", 3000); // Wait then fade out
                        setTimeout(() => alertBox.className = "", 3300);
                        return;
                    }

                    if(data.voucher_label) {
                        alertMsg.innerText = "Voucher Applied!";
                        alertIcon.className = "ri-checkbox-circle-line";
                        alertBox.className = "show success";
                        if(successLabel) {
                            successLabel.style.display = 'block';
                            appliedName.innerText = data.voucher_label;
                        }
                        setTimeout(() => alertBox.className = "success", 3000);
                        setTimeout(() => alertBox.className = "", 3300);
                    } else {
                        if(successLabel) successLabel.style.display = 'none';
                    }

                    const update = (id, val) => { const el = document.getElementById(id); if(el) el.innerText = val; };
                    update('summary-hours', data.hours + " Hours");
                    update('summary-subtotal', "RM " + data.subtotal);
                    update('summary-delivery', "RM " + data.delivery_fee);
                    update('summary-discount', "- RM " + data.discount);
                    update('summary-deposit', "RM " + data.deposit);
                    update('summary-total', "RM " + data.grand_total);
                    update('summary-stamps', "+ " + data.stamps + " Stamps");
                })
                .catch(err => console.error(err));
            };

            // --- 4. DATE/TIME SYNC & CONSTRAINTS ---
            const startD = document.getElementById("start_date_visual");
            const startT = document.getElementById("start_time_visual");
            const startH = document.getElementById("start_time_hidden");
            const endD = document.getElementById("end_date_visual");
            const endT = document.getElementById("end_time_visual");
            const endH = document.getElementById("end_time_hidden");

            const updateInputs = () => {
                if (startD && startT && startH) {
                    if (startD.value && startT.value) startH.value = startD.value + "T" + startT.value;
                }
                if (endD && endT && endH) {
                    if (endD.value && endT.value) endH.value = endD.value + "T" + endT.value;
                }

                // Validation: 24h Advance Check
                if (startH && startH.value) {
                    const s = new Date(startH.value);
                    const limit = new Date(new Date().getTime() + 12 * 60 * 60 * 1000);
                    if (s < limit) {
                        alert("Bookings must be made at least 12 hours in advance.");
                        if(startD) startD.value = ""; 
                        startH.value = ""; 
                        return;
                    }
                }
                // Validation: 1h Duration Check
                if (startH && startH.value && endH && endH.value) {
                    const s = new Date(startH.value);
                    const e = new Date(endH.value);
                    if (e < new Date(s.getTime() + 60 * 60 * 1000)) {
                        alert("Return time must be at least 1 hour after pickup time.");
                        if(endD) endD.value = ""; 
                        endH.value = ""; 
                        return;
                    }
                }
                calculatePrice();
            };

            [startD, startT, endD, endT].forEach(el => { if(el) el.addEventListener("change", updateInputs); });
            
            // --- 5. SESSION TIMER ---
            const timerDisplay = document.getElementById("timer-display");
            if (timerDisplay) {
                let timeLeft = 600;
                const timerInt = setInterval(() => {
                    let m = Math.floor(timeLeft / 60);
                    let s = timeLeft % 60;
                    timerDisplay.innerText = m + ":" + (s < 10 ? '0' : '') + s;
                    if (timeLeft <= 0) {
                        clearInterval(timerInt);
                        alert("Session expired!");
                        window.location.reload();
                    }
                    timeLeft--;
                }, 1000);
            }

            // Initialize
            document.addEventListener("DOMContentLoaded", () => {
                handlePickupChange();
                handleSameLocationChange();
                updateInputs();

                // 3. AVAILABILITY CHECK
                let bookedRanges = [];

                function fetchAvailability() {
                    const vehicleId = document.querySelector('input[name="vehicle_id"]').value;
                    fetch(`/booking/vehicle/${vehicleId}/availability?_=${new Date().getTime()}`)
                        .then(res => res.json())
                        .then(data => {
                            bookedRanges = data.active_bookings.map(r => ({
                                start: new Date(r.start),
                                end: new Date(r.end)
                            }));
                            updateAvailabilityUI();
                        });
                }

                function regenerateEndOptions() {
                    const sTime = startT.value; 
                    let mins = "00";
                    if (sTime) {
                        const p = sTime.split(':');
                        if (p.length === 2) mins = p[1];
                    }

                    // Preserve hour
                    const curVal = endT.value;
                    let curHour = -1;
                    if (curVal) curHour = parseInt(curVal.split(':')[0]);

                    endT.innerHTML = '<option value="" disabled ' + (!curVal ? 'selected' : '') + '>Select Time</option>';

                    for (let i = 7; i < 24; i++) {
                        const hStr = i.toString().padStart(2, '0');
                        const val = `${hStr}:${mins}`;
                        const opt = document.createElement('option');
                        opt.value = val;
                        opt.textContent = val;
                        if (i === curHour) opt.selected = true;
                        endT.appendChild(opt);
                    }
                    updateAvailabilityUI();
                }

                function updateAvailabilityUI() {
                    const selectedDate = startD.value;
                    const startTime = startT.value;
                    const endDate = endD.value;

                    // Helper: Strict Overlap Check (Touching is OKAY)
                    // Returns true if range A (startA, endA) strictly overlaps range B (startB, endB)
                    // Condition for overlapping: startA < endB && endA > startB
                    const isStrictOverlapping = (startA, endA, startB, endB) => {
                        return startA < endB && endA > startB;
                    };

                    // 1. Availability (Start Time)
                    if (selectedDate) {
                        Array.from(startT.options).forEach(opt => {
                            const time = opt.value;
                            if(!time) return;
                            
                            const myStart = new Date(selectedDate + 'T' + time);
                            const myMinEnd = new Date(myStart.getTime() + 60 * 60 * 1000); // +1 Hour min duration
                            
                            // Check if this projected minimal booking overlaps anything
                            const isBooked = bookedRanges.some(r => isStrictOverlapping(myStart, myMinEnd, r.start, r.end));

                            if (isBooked) {
                                opt.disabled = true;
                                opt.style.color = '#ccc';
                                opt.text = time + " (Unavailable)";
                            } else {
                                opt.disabled = false;
                                opt.style.color = '';
                                opt.text = time;
                            }
                        });
                    }
                    
                    // 2. End Time Logic (Availability AND Min Duration)
                    if (endDate) {
                         // Base restriction: Start Time + 1hr (if same day)
                         let minEndMinutes = -1;
                         if (selectedDate && startTime && selectedDate === endDate) {
                             const [h, m] = startTime.split(':').map(Number);
                             minEndMinutes = (h * 60 + m) + 60;
                         }

                         let currentStart = null;
                         if (selectedDate && startTime) {
                             currentStart = new Date(selectedDate + 'T' + startTime);
                         }

                         Array.from(endT.options).forEach(opt => {
                             const time = opt.value;
                             if(!time) return; // Skip placeholder

                             // A. STRICT Availability Check
                             // We check if the full range [currentStart, currentEnd] overlaps any booking
                             let isBooked = false;
                             let isSlotBooked = false;
                             
                             if (currentStart) {
                                 const currentEnd = new Date(endDate + 'T' + time);
                                 // Check if our hypothetical booking overlaps
                                 isBooked = bookedRanges.some(r => isStrictOverlapping(currentStart, currentEnd, r.start, r.end));
                             } else {
                                 // Fallback if no start time selected yet: just check if the End Point is free (loose check)
                                 const pt = new Date(endDate + 'T' + time);
                                 isSlotBooked = bookedRanges.some(r => pt >= r.start && pt <= r.end); 
                             }

                             // B. Min Duration Check
                             let isTooShort = false;
                             if (minEndMinutes > -1) {
                                 const [oh, om] = time.split(':').map(Number);
                                 if ((oh * 60 + om) < minEndMinutes) isTooShort = true;
                             }

                             if (isBooked || isSlotBooked || isTooShort) {
                                 opt.disabled = true;
                                 opt.style.color = '#ccc';
                                 if (isBooked || isSlotBooked) opt.text = time + " (Booked)";
                             } else {
                                 opt.disabled = false;
                                 opt.style.color = '';
                                 opt.text = time;
                             }
                         });
                         
                         // Reset if current selection is invalid
                         if(endT.value) {
                             if(endT.options[endT.selectedIndex].disabled) endT.value = "";
                         }
                    }

                     // Check Date full overlap logic
                     if(startD.value) {
                         const dateStart = new Date(startD.value + 'T00:00');
                         const dateEnd = new Date(startD.value + 'T23:59');
                         const fullyBooked = bookedRanges.some(range => range.start <= dateStart && range.end >= dateEnd);
                         if(fullyBooked) {
                             alert("This date is fully booked. Please select another date.");
                             startD.value = "";
                         }
                     }
                }
                
                if(startD) startD.addEventListener('change', updateAvailabilityUI);
                if(startT) {
                    startT.addEventListener('change', regenerateEndOptions);
                }
                if(endD) endD.addEventListener('change', updateAvailabilityUI); // Trigger when end date updates
                fetchAvailability(); // Load on start
                
                // Init regeneration on load if value exists
                if(startT && startT.value) regenerateEndOptions();
            });

            // Other Listeners
            const pSel = document.getElementById("pickup_location");
            if(pSel) pSel.addEventListener("change", handlePickupChange);
            const vSel = document.querySelector('select[name="selected_voucher_id"]');
            if(vSel) vSel.addEventListener("change", calculatePrice);
            const mCode = document.querySelector('input[name="manual_code"]');
            if(mCode) mCode.addEventListener('change', calculatePrice);
        })();

        function toggleEdit(sectionId) {
            const section = document.getElementById(sectionId);
            if(!section) return;
            
            // 1. Remove readonly from inputs
            const inputs = section.querySelectorAll('input[readonly]');
            inputs.forEach(input => {
                input.removeAttribute('readonly');
                // Optional: Flash visual effect
                input.style.backgroundColor = '#fff';
                input.style.color = '#15191d';
                input.style.cursor = 'text';
            });

            // 2. Hide toggle button
            const btn = section.querySelector('.btn-edit');
            if(btn) btn.style.display = 'none';
            
            // 3. Hide hint text
            const hint = section.querySelector('.autofill-hint');
            if(hint) hint.style.display = 'none';

             // 4. Hide notice
            const notice = section.querySelector('.autofill-notice');
            if(notice) notice.style.display = 'none';

            // 5. Focus first input
            if(inputs.length > 0) inputs[0].focus();
        }
    </script>
    @include('partials.rate-modal-global')
  </body>
</html>
