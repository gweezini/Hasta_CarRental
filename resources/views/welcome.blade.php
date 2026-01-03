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
        --primary-color: #ec5a29;
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
        gap: 1rem;
      }

      .mobile-only {
        display: none;
      }

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
        display: none;
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

      .range__container {
        scroll-margin-top: 100px;
      }

      .range__grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
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

      @media (max-width: 1024px) {
        .range__card {
          flex: 0 1 calc(50% - 2rem);
        }
      }

      @media (max-width: 768px) {
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

        .range__card {
          flex: 0 1 100%;
        }

        .footer__container {
          grid-template-columns: repeat(2, 1fr);
        }

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
    </style>
    <title>Hasta Car Rental</title>
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
            <a href="{{ route('login') }}">Login</a>
          </li>
          <li class="mobile-only">
            <a href="{{ route('register') }}">Register</a>
          </li>
        </ul>
        <div class="nav__btn">
          <a href="{{ route('login') }}" class="btn btn-transparent">Login</a>
          <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        </div>
      </nav>

      <div class="header__container" id="home">
        <h1>AFFORDABLE CAR RENTAL SERVICE</h1>
        
       <form action="{{ route('home') }}" method="GET">
          <div class="input__group">
            <label for="pickup">Pick up location</label>
            <input
              type="text"
              id="pickup"
              name="location"
              value="{{ request('location') }}" 
              placeholder="Student Mall, UTM"
              required
            />
          </div>

          <div class="input__group">
            <label for="start">Start Date & Time</label>
              <div class="date-time-wrapper">
                <input type="date" name="start_date" id="start_date" 
                       value="{{ request('start_date') }}" 
                       min="{{ date('Y-m-d') }}" required />
                <select name="start_time" style="flex: 1; padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 1rem; color: #737373; outline: none;" required>
                    <option value="" disabled {{ !request('start_time') ? 'selected' : '' }}>Select Time</option>
                    @for ($i = 0; $i < 1440; $i += 10)
                        @php $timeVal = sprintf('%02d:%02d', floor($i / 60), $i % 60); @endphp
                        <option value="{{ $timeVal }}" {{ request('start_time') == $timeVal ? 'selected' : '' }}>{{ $timeVal }}</option>
                    @endfor
                </select>
              </div>
          </div>
          
          <div class="input__group">
            <label for="stop">End Date & Time</label>
              <div class="date-time-wrapper">
                <input type="date" name="stop_date" id="stop_date" 
                       value="{{ request('stop_date') }}" 
                       min="{{ date('Y-m-d') }}" required />
                <select name="stop_time" style="flex: 1; padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 1rem; color: #737373; outline: none;" required>
                    <option value="" disabled {{ !request('stop_time') ? 'selected' : '' }}>Select Time</option>
                    @for ($i = 0; $i < 1440; $i += 60)
                        @php $timeVal = sprintf('%02d:%02d', floor($i / 60), $i % 60); @endphp
                        <option value="{{ $timeVal }}" {{ request('stop_time') == $timeVal ? 'selected' : '' }}>{{ $timeVal }}</option>
                    @endfor
                </select>
              </div>
          </div>

          <div class="checkbox_group">
            <input type="checkbox" id="same-location" checked />
            <label for="same-location">Same as pick up location</label>
          </div>

          <div class="input__group dropoff_group" id="dropoff-input-group">
            <label for="dropoff">Drop off location</label>
            <input type="text" id="dropoff" placeholder="Different location" />
          </div>

          <button type="submit" class="btn">
            <i class="ri-search-line"></i>
          </button>
        </form>
      </div>
    </header>

    <section class="section__container range__container" id="vehicles">
      <h2 class="section__header">OUR RANGE OF VEHICLES</h2>

      <div class="range__grid">
        @if($vehicles->isEmpty())
          <div style="text-align: center; width: 100%; padding: 2rem;">
            <p style="font-size: 1.2rem; color: #737373;">
              <i class="ri-car-line" style="font-size: 2rem;"></i><br>
              No vehicles available for the selected dates. Please try different dates.
            </p>
          </div>
        @else
        @foreach($vehicles as $vehicle)
        @php
            // Use the calculated flag, default to true if not set (e.g. direct page load without search)
            $available = $vehicle->is_available ?? true; 
            // Also check generic status if needed, though controller handles 'Available'/'Rented' status inclusion
            if($vehicle->status !== 'Available' && $vehicle->status !== 'Rented') $available = false; 
        @endphp
        <div
          class="range__card {{ !$available ? 'unavailable' : '' }}"
        >
          <img
            src="{{ asset('images/' . $vehicle->vehicle_image) }}"
            alt="{{ $vehicle->model }}"
          />

          <div class="range__details">
            <h4 class="card__title">
              {{ $vehicle->brand }} {{ $vehicle->model }}
            </h4>

            <p style="font-size: 0.85rem; color: #666; margin: 0.5rem 0;">
              <strong>Plate:</strong> {{ $vehicle->plate_number }}
            </p>

            <p class="card__price">
              Starting from
              <strong>RM {{ $vehicle->price_per_hour }} / hour</strong>
            </p>

            <hr class="card__divider" />

            <div class="card__tags">
              <span class="card__tag"> {{ $vehicle->capacity }} Seater </span>
            </div>

            <p class="card__location">
              <i class="ri-map-pin-line"></i> UTM Campus
            </p>
            
            @if(!$available)
                <div style="background-color: #fee2e2; color: #dc2626; padding: 10px; border-radius: 5px; font-size: 0.8rem; margin-top: 10px; text-align: center;">
                    <i class="ri-error-warning-line"></i> This vehicle is unavailable for selected rental period
                </div>
            @endif

            <div class="card__btn" style="background-color: transparent;">
            @if($available)
                @auth
                    <a href="{{ url('/booking/' . $vehicle->id . '?start_date=' . request('start_date') . '&start_time=' . request('start_time') . '&stop_date=' . request('stop_date') . '&stop_time=' . request('stop_time')) }}" class="btn" 
                       style="background-color: var(--primary-color); color: white; width: 100%; display: block; text-align: center; text-decoration: none;">
                       Rent Now
                    </a>
               @else
                    <a href="{{ route('login') }}" class="btn" 
                       style="background-color: var(--primary-color); color: white; width: 100%; display: block; text-align: center; text-decoration: none;">
                       Rent Now
                    </a>
               @endauth
            @else
                <button class="btn" disabled style="background-color: gray; color: white; cursor: not-allowed; opacity: 0.7; width: 100%;">
                Not Available
                </button>
            @endif
            </div>
          </div>
        </div>
        @endforeach
        @endif
      </div>
    </section>

    <br /><br />

    <footer>
      <div class="section__container footer__container">
        <div class="footer__col">
          <h4>Resources</h4>
          <ul class="footer__links">
            <li><a href="#">Installation Manual</a></li>
            <li><a href="#">Release Note</a></li>
            <li><a href="#">Community Help</a></li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Company</h4>
          <ul class="footer__links">
            <li><a href="#">About Us</a></li>
            <li><a href="#">Career</a></li>
            <li><a href="#">Press</a></li>
            <li><a href="#">Support</a></li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Product</h4>
          <ul class="footer__links">
            <li><a href="#">Demo</a></li>
            <li><a href="#">Security</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Features</a></li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Follow Us</h4>
          <ul class="footer__socials">
            <li>
              <a href="https://www.facebook.com/hastatraveltours"
                ><i class="ri-facebook-fill"></i
              ></a>
            </li>
            <li>
              <a href="https://www.instagram.com/hastatraveltours/?hl=en"
                ><i class="ri-instagram-fill"></i
              ></a>
            </li>
            <li>
              <a href="https://x.com/hastacarrental"
                ><i class="ri-twitter-fill"></i
              ></a>
            </li>
            <li>
              <a href="https://www.linkedin.com/company/hasta-travel/"
                ><i class="ri-linkedin-fill"></i
              ></a>
            </li>
          </ul>
        </div>
      </div>
      <div class="footer__bar">
        <p style="color: var(--text-light); text-align: center; padding: 2rem">
          © 2025 Hasta Car Rental. All rights reserved.
        </p>
      </div>
    </footer>

    <script>
      const menuBtn = document.getElementById("menu-btn");
      const navLinks = document.getElementById("nav-links");

      menuBtn.addEventListener("click", () => {
        navLinks.classList.toggle("open");
      });

      const navbar = document.getElementById("navbar");
      window.addEventListener("scroll", () => {
        if (window.scrollY > 50) {
          navbar.classList.add("nav__fixed");
        } else {
          navbar.classList.remove("nav__fixed");
        }
      });

      const sameLocationCheckbox = document.getElementById("same-location");
      const dropoffInputGroup = document.getElementById("dropoff-input-group");

      function toggleDropoff() {
        if (sameLocationCheckbox.checked) {
          dropoffInputGroup.style.display = "none";
        } else {
          dropoffInputGroup.style.display = "flex";
        }
      }

      sameLocationCheckbox.addEventListener("change", toggleDropoff);
      toggleDropoff();
    </script>
    <script>
      const startInput = document.getElementById('start_date');
      const stopInput = document.getElementById('stop_date');

      startInput.addEventListener('change', function() {
        stopInput.min = this.value;
        if (stopInput.value && stopInput.value < this.value) {
            stopInput.value = "";
        }
      });

    </script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
          const startDateInput = document.getElementById('start_date');
          // Use a specific selector or the same one but ensure it's found
          const startTimeInput = document.querySelector('[name="start_time"]');

          function checkTime() {
              if(!startDateInput || !startTimeInput) return;
              
              const dateVal = startDateInput.value;
              const timeVal = startTimeInput.value;

              if (dateVal && timeVal) {
                  // Construct date object (Local time)
                  const pickupDate = new Date(dateVal + 'T' + timeVal);
                  const now = new Date();
                  
                  // Add 24 hours to current time
                  const minTime = new Date(now.getTime() + 24 * 60 * 60 * 1000);

                  if (pickupDate < minTime) {
                      alert("Invalid Pick Up Time! Bookings must be made at least 24 hours in advance.");
                      startTimeInput.value = ""; 
                  }
              }
          }

          if(startDateInput) {
            startDateInput.addEventListener('change', checkTime);
          }
          if(startTimeInput) {
            startTimeInput.addEventListener('change', checkTime);
            startTimeInput.addEventListener('blur', checkTime); // Extra check on blur
          }

          // --- DYNAMIC END TIME RESTRICTION (Start + 1hr) ---
          const stopInput = document.getElementById('stop_date');
          const stopTimeSelect = document.querySelector('select[name="stop_time"]');

          function regenerateEndTimeOptions() {
              if (!startTimeInput || !stopTimeSelect) return;

              const startTime = startTimeInput.value; // HH:MM
              let minutes = "00";
              
              if (startTime) {
                  const parts = startTime.split(':');
                  if (parts.length === 2) {
                      minutes = parts[1];
                  }
              }

              // Save current selection if possible
              const currentVal = stopTimeSelect.value;
              let currentHour = -1;
              if (currentVal) {
                  currentHour = parseInt(currentVal.split(':')[0]);
              }

              // Clear existing
              stopTimeSelect.innerHTML = '<option value="" disabled ' + (!currentVal ? 'selected' : '') + '>Select Time</option>';

              // Generate New Options (00:mm to 23:mm)
              for (let i = 0; i < 24; i++) {
                  const hourStr = i.toString().padStart(2, '0');
                  const val = `${hourStr}:${minutes}`;
                  const opt = document.createElement('option');
                  opt.value = val;
                  opt.textContent = val;
                  
                  // Try to preserve previous hour selection
                  if (i === currentHour) {
                      opt.selected = true;
                  }
                  
                  stopTimeSelect.appendChild(opt);
              }
              
              // Verify/Update Constraints after regeneration
              updateEndTimeConstraints();
          }

          function updateEndTimeConstraints() {
              if(!startDateInput || !startTimeInput || !stopInput || !stopTimeSelect) return;

              const startDate = startDateInput.value;
              const startTime = startTimeInput.value;
              const stopDate = stopInput.value;

              // 1. Min Date: End Date cannot be before Start Date
              if (startDate) {
                  stopInput.min = startDate;
                  if (stopInput.value && stopInput.value < startDate) {
                      stopInput.value = startDate;
                  }
              }

              // 2. Min Time: If End Date == Start Date, End Time > Start Time + 1hr
              if (startDate && stopInput.value && startDate === stopInput.value && startTime) {
                  const [h, m] = startTime.split(':').map(Number);
                  const startMinutes = h * 60 + m;
                  const minEndMinutes = startMinutes + 60; // +1 Hour

                  Array.from(stopTimeSelect.options).forEach(opt => {
                      if (opt.value) {
                          const [oh, om] = opt.value.split(':').map(Number);
                          const optMinutes = oh * 60 + om;

                          if (optMinutes < minEndMinutes) {
                              opt.disabled = true;
                              opt.style.color = '#ccc';
                          } else {
                              opt.disabled = false;
                              opt.style.color = '';
                          }
                      }
                  });
                  
                  // Helper: If currently selected value is now disabled, reset it
                  if (stopTimeSelect.value) {
                       const [sh, sm] = stopTimeSelect.value.split(':').map(Number);
                       if ((sh * 60 + sm) < minEndMinutes) {
                           stopTimeSelect.value = "";
                       }
                  }

              } else {
                  // Different dates or no start time: Enable all (except default placeholder)
                  Array.from(stopTimeSelect.options).forEach(opt => {
                      if (opt.value) {
                          opt.disabled = false;
                          opt.style.color = '';
                      }
                  });
              }
          }

          // Attach Listeners
          if(startDateInput) startDateInput.addEventListener('change', updateEndTimeConstraints);
          if(startTimeInput) {
              startTimeInput.addEventListener('change', () => {
                  regenerateEndTimeOptions();
                  // updateEndTimeConstraints is called inside regenerate
              });
          }
          if(stopInput) stopInput.addEventListener('change', updateEndTimeConstraints);
          
          // Init logic (in case of page reload with values)
          if(startTimeInput && startTimeInput.value) {
              regenerateEndTimeOptions();
          } else {
              updateEndTimeConstraints();
          }
      });
    </script>
    <script>
      // Auto-scroll to vehicles section if search was performed
      document.addEventListener("DOMContentLoaded", function() {
          const urlParams = new URLSearchParams(window.location.search);
          if (urlParams.has('start_date') || urlParams.has('location')) {
              const vehicleSection = document.getElementById("vehicles");
              if (vehicleSection) {
                  vehicleSection.scrollIntoView({ behavior: 'smooth' });
              }
          }
      });
    </script>
  </body>
</html>