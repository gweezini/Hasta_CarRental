<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <style>
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
        padding: 5rem 1rem;
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
        background-color: var(--text-dark);
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
        gap: 1rem;
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
          z-index: -1;
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
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
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

/* Responsive for Mobile */
@media (max-width: 992px) {
  .booking__grid {
    grid-template-columns: 1fr;
  }
  .booking__summary {
    position: static;
  }
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
input[type="radio"]:checked + .payment-label-content .radio-circle::after {
    content: "";
    width: 10px;
    height: 10px;
    background-color: var(--primary-color);
    border-radius: 50%;
    display: block;
}
    </style>
</head>

<body>
    <header>
      <nav id="navbar">
        <div class="nav__header">
          <div class="nav__logo">
            <a href="#home">
              <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta" />
            </a>
          </div>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-line"></i>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="#home">Dashboard</a></li>
          <li><a href="#vehicles">Vehicles</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="#about">About Us</a></li>

<li class="mobile-only">
            <a href="{{ route('profile.edit') }}">Profile</a>
          </li>

          <li class="mobile-only">
             <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" style="background:none; border:none; padding:0; color:inherit; font:inherit; cursor:pointer;">
                    Logout
                </button>
             </form>
          </li>
        </ul>

        <div class="nav__btn">
          
          <a href="{{ route('profile.edit') }}" class="btn btn-transparent">
             Profile
          </a>

          <form method="POST" action="{{ route('logout') }}" style="display: inline-block;">
            @csrf
            <button type="submit" class="btn btn-primary" style="padding: 20px">
                Logout
            </button>
          </form>

        </div>
      </nav>
      <section class="section__container">
  <h2 class="section__header" style="text-align: left; margin-bottom: 2rem;">Complete Your Booking</h2>
  
  <div class="booking__grid">
  
  <div class="booking__form">
    <form id="bookingForm" action="/confirm-booking" method="POST" enctype="multipart/form-data">
      @csrf
      
      <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

      <div class="form__section">
        <h3><i class="ri-calendar-check-line"></i> Rental Period</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="input__group">
                <label>Start Date</label>
                <input type="datetime-local" name="start_time" value="{{ request('start_time') }}" required />
            </div>
            <div class="input__group">
                <label>End Date</label>
                <input type="datetime-local" name="end_time" value="{{ request('end_time') }}" required />
            </div>
        </div>
      </div>

      <div class="form__section">
        <h3><i class="ri-ticket-line"></i> Vouchers</h3>
        <p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 1rem;">
            Select a claimed voucher OR enter a code.
        </p>

        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
            <div class="input__group">
                <label>My Rewards</label>
                <select name="selected_voucher_id" style="width: 100%; padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px;">
                    <option value="">-- Select a Reward --</option>
                    @foreach($myVouchers as $v)
                        <option value="{{ $v->id }}" {{ request('selected_voucher_id') == $v->id ? 'selected' : '' }}>
                            {{ $v->name }} (RM {{ $v->amount }} Off)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="input__group">
                <label>Or Enter Promo Code</label>
                <input type="text" name="manual_code" value="{{ request('manual_code') }}" placeholder="e.g. HASTA2024" style="text-transform: uppercase;">
            </div>
        </div>

        <button 
            type="button" 
            onclick="updatePrice()"
            class="btn btn-transparent" 
            style="margin-top: 15px; width: 100%; border: 1px solid var(--primary-color); color: var(--primary-color);">
            <i class="ri-refresh-line"></i> Apply Voucher & Update Price
        </button>
        <small style="color: red; display: block; margin-top: 5px;">* Apply voucher BEFORE uploading receipt/license.</small>
      </div>

      <div class="form__section">
        <h3><i class="ri-user-line"></i> Personal Information</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="input__group">
            <label>Full Name</label>
            <input type="text" name="name" value="{{ Auth::user()->name }}" required />
          </div>
          <div class="input__group">
            <label>Phone Number</label>
            <input type="tel" name="phone" value="{{ Auth::user()->phone }}" placeholder="+60..." required />
          </div>
        </div>
      </div>

      <div class="form__section">
        <h3><i class="ri-alarm-warning-line"></i> Emergency Contact</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="input__group">
                <label>Contact Name</label>
                <input type="text" name="emergency_name" value="{{ Auth::user()->emergency_name }}" required />
            </div>
            <div class="input__group">
                <label>Contact Number</label>
                <input type="tel" name="emergency_contact" value="{{ Auth::user()->emergency_contact }}" required />
            </div>
        </div>
        <div class="input__group" style="margin-top: 1rem;">
            <label>Relationship</label>
            <input type="text" name="emergency_relationship" value="{{ Auth::user()->emergency_relationship }}" required />
        </div>
      </div>

      <div class="form__section">
        <h3><i class="ri-bank-card-line"></i> Payment Method</h3>
        
        <div class="payment-card">
            <input type="radio" name="payment_method" id="pay-qr" value="qr_pay" checked style="display: none;">
            <label for="pay-qr" class="payment-label-content">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div class="radio-circle"></div>
                    <div style="flex: 1;"><strong>DuitNow QR</strong></div>
                    <i class="ri-qr-code-line" style="font-size: 1.5rem; color: var(--primary-color);"></i>
                </div>
                <div class="payment-details-box" style="text-align: center;">
                    <img src="{{ asset('images/paymentqr.png') }}" alt="QR" style="max-width: 200px;">
                </div>
            </label>
        </div>

        <div class="payment-card">
            <input type="radio" name="payment_method" id="pay-bank" value="bank_transfer" style="display: none;">
            <label for="pay-bank" class="payment-label-content">
                <div style="display: flex; align-items: center; gap: 15px;">
                     <div class="radio-circle"></div>
                    <div style="flex: 1;"><strong>Manual Transfer</strong></div>
                    <i class="ri-bank-line" style="font-size: 1.5rem; color: var(--primary-color);"></i>
                </div>
                <div class="payment-details-box">
                    <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid var(--primary-color);">
                        <p><strong>CIMB Bank</strong> | Hasta Car Rental</p>
                        <p style="font-size: 1.1rem; color: var(--primary-color); font-weight: 700;">8600123456</p>
                    </div>
                </div>
            </label>
        </div>

        <div class="input__group" style="margin-top: 1rem;">
            <label>Upload Payment Receipt</label>
            <input type="file" name="receipt_image" required accept="image/*, .pdf" />
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.2rem; margin-top: 1rem;">
        Confirm Booking
      </button>
    </form>
  </div>

  <div class="booking__summary">
    <h3>Booking Summary</h3>
    <img src="{{ asset('images/' . $vehicle->vehicle_image) }}" alt="Car" style="border-radius: 8px; margin-bottom: 1rem; width: 100%; object-fit: cover;">
    
    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
        <span>Rate</span>
        <strong>RM {{ $vehicle->price_per_hour }} / hr</strong>
    </div>

    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
        <span>Duration</span>
        <strong>{{ $hours ?? 0 }} hours</strong>
    </div>

    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
        <span>Subtotal</span>
        <strong>RM {{ number_format($subtotal ?? 0, 2) }}</strong>
    </div>

    @if(isset($discount) && $discount > 0)
    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #28a745;">
        <span>Discount</span>
        <strong>- RM {{ number_format($discount, 2) }}</strong>
    </div>
    @endif

    @if(isset($voucherMessage) && $voucherMessage != "")
        <small style="display: block; text-align: right; color: {{ $discount > 0 ? '#28a745' : 'red' }}; margin-bottom: 10px;">
            {{ $voucherMessage }}
        </small>
    @endif

    <hr style="margin: 1rem 0; opacity: 0.2;">

    <div style="display: flex; justify-content: space-between; font-size: 1.4rem; color: var(--primary-color);">
        <span>Total</span>
        <strong>RM {{ number_format($total ?? 0, 2) }}</strong>
    </div>
  </div>
</div>

<script>
    function updatePrice() {
        var form = document.getElementById('bookingForm');
        // Force URL to current page (Booking Page)
        form.action = "{{ url('/booking/' . $vehicle->id) }}";
        // Force method to GET
        form.method = "GET";
        // Submit
        form.submit();
    }
</script>
</section>
</body>



</html>