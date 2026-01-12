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

      .section__subheader {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-light);
        text-align: center;
        margin-bottom: 2rem;
        margin-top: -3rem;
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
        display: flex;
        flex-direction: column;
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
        background-color: transparent;
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

      /* Notification Styles */
      .notif-wrapper { position: relative; margin-right: 5px; }
      .notif-btn { background: none; border: none; cursor: pointer; color: var(--white); font-size: 1.5rem; position: relative; display: flex; align-items: center; transition: 0.3s; }
      .notif-btn:hover { color: var(--primary-color); }
      .notif-badge { position: absolute; top: 0; right: 0; width: 10px; height: 10px; background-color: #ef4444; border-radius: 50%; border: 2px solid #2d3748; }
      .notif-dropdown { position: absolute; right: 0; top: 100%; margin-top: 15px; width: 320px; background-color: var(--white); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; z-index: 1001; text-align: left; color: var(--text-dark); }
      .notif-header { padding: 15px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; font-weight: 700; font-size: 0.9rem; background: #f9f9f9; }
      .notif-item { padding: 15px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: start; gap: 10px; transition: 0.2s; text-decoration: none; color: inherit; }
      .notif-item:hover { background-color: #f5f5f5; }
      .notif-icon { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
      .notif-icon.success { background: #dcfce7; color: #16a34a; }
      .notif-icon.error { background: #fee2e2; color: #dc2626; }
      .notif-icon.info { background: #dbeafe; color: #2563eb; }
      .notif-content p { font-size: 0.85rem; font-weight: 500; line-height: 1.4; margin-bottom: 4px; }
      .notif-content span { font-size: 0.7rem; color: var(--text-light); }
      .notif-footer { padding: 12px; text-align: center; background: #f9f9f9; border-top: 1px solid #f0f0f0; }
      .notif-footer a { font-size: 0.8rem; font-weight: 700; color: var(--primary-color); }


      /* Header/Hero Section */
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
        width: 100%;
        margin-inline: auto;
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

      .input__group input, .input__group select {
        width: 100%;
        padding: 10px;
        border: 1px solid var(--extra-light);
        border-radius: 5px;
        font-size: 1rem;
        color: var(--text-dark);
        outline: none;
      }

      .date-time-wrapper {
        display: flex;
        gap: 5px;
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
        padding: 12px;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 1.5rem;
      }

      /* Vehicle Cards */
      .range__grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; }
      .range__card { background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border: 1px solid #f0f0f0; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.3s ease; width: 350px; flex: 1 1 300px; max-width: 400px; }
      .range__card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); }
      .range__card img { width: 100%; height: 220px; object-fit: contain; background-color: #ffffff; padding: 20px; border-bottom: 1px solid #f5f5f5; }
      .range__details { padding: 20px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
      .card__title { font-size: 1.2rem; font-weight: 700; color: #15191d; margin: 0; }
      .card__price { font-size: 0.9rem; color: #737373; margin: 0; }
      .card__price strong { color: #ec5a29; font-size: 1.1rem; }
      .card__divider { border: none; border-top: 1px solid #eee; margin: 5px 0; }
      .card__tags { display: flex; gap: 8px; }
      .card__tag { background-color: #f4f4f4; color: #555; padding: 5px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; }
      .card__location { font-size: 0.85rem; color: #999; display: flex; align-items: center; gap: 5px; margin-top: auto; }
      .card__btn { width: 100%; margin-top: 15px; }

      .range__card.unavailable { background-color: #f5f5f5; opacity: 0.7; pointer-events: none; filter: grayscale(100%); }
      .range__card.unavailable .card__btn button { background-color: #999; cursor: not-allowed; }
      
      .mobile-only { display: none; }

      footer { background-color: #2d3748; padding-top: 5rem; margin-top: 5rem; }
      .footer__container { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 2rem; border-bottom: 1px solid var(--text-light); padding-bottom: 2rem; }
      .footer__col h4 { margin-bottom: 2rem; font-size: 1.2rem; font-family: var(--header-font); color: var(--white); text-transform: uppercase; }
      .footer__links a { color: var(--text-light); display: block; margin-bottom: 1rem; }
      .footer__links a:hover { color: var(--primary-color); }
      .footer__socials { display: flex; gap: 1rem; list-style: none; }
      .footer__socials a {
        padding: 8px 12px;
        font-size: 1.25rem;
        color: var(--text-light);
        border: 2px solid var(--text-light);
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .footer__socials a:hover { color: var(--primary-color); border-color: var(--primary-color); }
      .footer__bar { border-top: 1px solid rgba(255, 255, 255, 0.1); }

      @media (max-width: 768px) {
        .nav__menu__btn { display: block; }
        .nav__links { position: absolute; top: 100%; left: 0; width: 100%; background-color: var(--primary-color); flex-direction: column; padding: 2rem; transform: translateY(-150%); transition: 0.5s; z-index: -1; }
        .nav__links.open { transform: translateY(0); }
        .nav__btn { display: none; }
        .mobile-only { display: block; width: 100%; }
        .mobile-only a { display: block; text-align: center; border: 1px solid var(--white); border-radius: 5px; margin-top: 10px; padding: 10px; background-color: transparent; }
        .header-search form { grid-template-columns: 1fr; }
        .header-search form .btn { grid-column: 1; justify-self: stretch; }
        .footer__container { grid-template-columns: repeat(2, 1fr); }
      }
    </style>
    <title>Search Results | Hasta Car Rental</title>
  </head>
  <body>
    <header>
      <!-- Navigation -->
      <nav id="navbar">
        <div class="nav__header">
          <div class="nav__logo">
            <a href="{{ route('home') }}">
              <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta" />
            </a>
          </div>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-line"></i>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li><a href="{{ url('/#vehicles') }}">Vehicles</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="{{ url('/#about') }}">About Us</a></li>
          @auth
          <li><a href="{{ route('profile.edit') }}">My Profile</a></li>
          @endauth
          
          @guest
          <li class="mobile-only"><a href="{{ route('login') }}">Login</a></li>
          <li class="mobile-only"><a href="{{ route('register') }}">Register</a></li>
          @endguest
        </ul>

        <div class="nav__btn">
          @guest
            <a href="{{ route('login') }}" class="btn btn-transparent">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
          @else
            <!-- Notification & User dropdown (Simplified as in home) -->
            <div x-data="{ userOpen: false }" style="position: relative;">
                <button @click="userOpen = !userOpen" @click.away="userOpen = false" style="display: flex; align-items: center; gap: 0.5rem; background: none; border: none; cursor: pointer; outline: none;">
                    <img style="height: 36px; width: 36px; border-radius: 50%; object-fit: cover;" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ec5a29&color=fff" alt="Profile">
                    <span style="color: white; font-size: 0.875rem; font-weight: 500;" class="hidden md:block">{{ Auth::user()->name }}</span>
                    <i class="ri-arrow-down-s-line" style="color: white;"></i>
                </button>
                <div x-show="userOpen" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 12px; width: 12rem; background-color: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; z-index: 50; padding: 4px 0;">
                    <a href="{{ route('profile.edit') }}" style="display: block; padding: 8px 16px; font-size: 0.875rem; color: #374151;">My Profile</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; text-align: left; padding: 8px 16px; font-size: 0.875rem; color: #dc2626; background: none; border: none; cursor: pointer;">Logout</button>
                    </form>
                </div>
            </div>
          @endguest
        </div>
      </nav>

      <div class="header__container">
        <h1>AFFORDABLE CAR RENTAL SERVICE</h1>

        @if(session('error'))
            <div class="max-w-4xl mx-auto mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm text-left">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('search') }}" method="GET">
            <div class="input__group">
                <label for="pickup">Pick up location</label>
                <input type="text" id="pickup" name="location" value="{{ $location }}" placeholder="Student Mall, UTM" required />
            </div>
            <div class="input__group">
                <label for="start">Start Date & Time</label>
                <div class="date-time-wrapper">
                    <input type="date" name="start_date" id="start_date" value="{{ $start ? $start->format('Y-m-d') : '' }}" min="{{ date('Y-m-d') }}" required />
                    <select name="start_time" required>
                        <option value="" disabled>Select Time</option>
                        @for ($i = 420; $i < 1440; $i += 10)
                            @php $timeVal = sprintf('%02d:%02d', floor($i / 60), $i % 60); @endphp
                            <option value="{{ $timeVal }}" {{ $start && $start->format('H:i') == $timeVal ? 'selected' : '' }}>{{ $timeVal }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="input__group">
                <label for="stop">End Date & Time</label>
                <div class="date-time-wrapper">
                    <input type="date" name="stop_date" id="stop_date" value="{{ $end ? $end->format('Y-m-d') : '' }}" min="{{ date('Y-m-d') }}" required />
                    <select name="stop_time" required>
                        <option value="" disabled>Select Time</option>
                        @for ($i = 420; $i < 1440; $i += 60)
                             @php $timeVal = sprintf('%02d:%02d', floor($i / 60), $i % 60); @endphp
                            <option value="{{ $timeVal }}" {{ $end && $end->format('H:i') == $timeVal ? 'selected' : '' }}>{{ $timeVal }}</option>
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

    <!-- Available Vehicles -->
    <section class="section__container" id="available">
        <h2 class="section__header">Available Vehicles</h2>
        <p class="section__subheader">Ready for your journey</p>
        
        @if($availableVehicles->isEmpty())
            <div style="text-align: center; color: #777; padding: 2rem;">
                <i class="ri-car-line" style="font-size: 3rem; color: #ccc;"></i>
                <p class="mt-4">No vehicles available for the selected dates. Please try adjustment your time.</p>
            </div>
        @else
            <div class="range__grid">
                @foreach($availableVehicles as $vehicle)
                <div class="range__card">
                    <img src="{{ asset('images/' . $vehicle->vehicle_image) }}" alt="{{ $vehicle->model }}" />
                    <div class="range__details">
                        <h4 class="card__title">{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                        <p style="font-size: 0.85rem; color: #666; margin: 0.5rem 0;"><strong>Plate:</strong> {{ $vehicle->plate_number }}</p>
                        <p class="card__price">
                            Starting from <strong>RM {{ number_format($vehicle->pricingTier ? $vehicle->pricingTier->rules->min('price') : $vehicle->price_per_hour, 2) }} / hour</strong>
                            @include('partials.price-modal', ['vehicle' => $vehicle])
                        </p>
                        <hr class="card__divider" />
                        <div class="card__tags"><span class="card__tag"> {{ $vehicle->capacity }} Seater </span></div>
                        <p class="card__location"><i class="ri-map-pin-line"></i> UTM Campus</p>
                        <div class="card__btn">
                            @auth
                                <a href="{{ route('booking.show', ['id' => $vehicle->id, 'start_date' => $start->format('Y-m-d'), 'start_time' => $start->format('H:i'), 'stop_date' => $end->format('Y-m-d'), 'stop_time' => $end->format('H:i')]) }}" class="btn" style="background-color: var(--primary-color); color: white; width: 100%; display: block; text-align: center;">Rent Now</a>
                            @else
                                <a href="{{ route('login') }}" class="btn" style="background-color: var(--primary-color); color: white; width: 100%; display: block; text-align: center;">Rent Now</a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Unavailable Vehicles -->
    <section class="section__container" id="unavailable" style="background-color: #f3f4f6; padding-top: 3rem;">
        <h2 class="section__header">Unavailable Vehicles</h2>
        <p class="section__subheader">Currently booked for your selected time</p>

        @if($unavailableVehicles->isNotEmpty())
        <div class="range__grid">
            @foreach($unavailableVehicles as $vehicle)
            <div class="range__card unavailable">
                <img src="{{ asset('images/' . $vehicle->vehicle_image) }}" alt="{{ $vehicle->model }}" />
                <div class="range__details">
                    <h4 class="card__title">{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                    <p class="card__price">Starting from <strong>RM {{ number_format($vehicle->pricingTier ? $vehicle->pricingTier->rules->min('price') : $vehicle->price_per_hour, 2) }} / hour</strong></p>
                    <div class="card__btn">
                        <button class="btn" disabled>Not Available</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>

    <footer>
      <div class="section__container footer__container">
        <div class="footer__col">
          <h4>Hasta Car Rental</h4>
          <p style="color: var(--text-light); line-height: 1.6; margin-bottom: 1rem;">
            Experience the freedom of the road with our premium car rental services. 
            Reliable, affordable, and convenient vehicles for every journey.
          </p>
        </div>
        
        <div class="footer__col" id="about">
          <h4>Quick Links</h4>
          <ul class="footer__links">
            <li><a href="{{ route('about') }}">About Us</a></li>
            <li><a href="{{ route('faq') }}">FAQ</a></li>
            <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
            <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
          </ul>
        </div>

        <div class="footer__col" id="contact">
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
          
          <div style="margin-top: 1.5rem;">
              <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px;">
                  <i class="ri-phone-line" style="color: var(--primary-color);"></i> 
                  <span>Car Rental Inquiry:<br><strong style="color: #cbd5e1;">+60 11-1090 0700</strong></span>
              </p>
              <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px;">
                  <i class="ri-mail-line" style="color: var(--primary-color);"></i> 
                  <span>Support:<br><strong style="color: #cbd5e1;">hastatraveltours@gmail.com</strong></span>
              </p>
          </div>
          <br>
        </div>
      </div>
      <div class="footer__bar">
        <p style="color: var(--text-light); text-align: center; padding: 2rem">
          © 2026 Hasta Car Rental. All rights reserved.
        </p>
      </div>
    </footer>

    <script>
      const menuBtn = document.getElementById("menu-btn");
      const navLinks = document.getElementById("nav-links");
      const navbar = document.getElementById("navbar");

      menuBtn.addEventListener("click", () => {
        navLinks.classList.toggle("open");
      });

      window.onscroll = () => {
        if (window.scrollY > 20) {
            navbar.classList.add("nav__fixed");
        } else {
            navbar.classList.remove("nav__fixed");
        }
      };

      // Dropoff checkbox logic (same as home)
      const sameLocationCheckbox = document.getElementById('same-location');
      const dropoffInputGroup = document.getElementById('dropoff-input-group');
      if (sameLocationCheckbox && dropoffInputGroup) {
          sameLocationCheckbox.addEventListener('change', function() {
              if (this.checked) {
                  dropoffInputGroup.style.display = 'none';
              } else {
                  dropoffInputGroup.style.display = 'flex';
              }
          });
      }

      // Date constraints (Simple version)
      document.addEventListener("DOMContentLoaded", function() {
          const startInput = document.getElementById('start_date');
          const stopInput = document.getElementById('stop_date');
          const startTimeInput = document.querySelector('[name="start_time"]');

          function checkTime() {
              if(!startInput || !startTimeInput) return;
              const dateVal = startInput.value;
              const timeVal = startTimeInput.value;
              if (dateVal && timeVal) {
                  const pickupDate = new Date(dateVal + 'T' + timeVal);
                  const now = new Date();
                  const minTime = new Date(now.getTime() + 12 * 60 * 60 * 1000);
                  if (pickupDate < minTime) {
                      alert("Invalid Pick Up Time! Bookings must be made at least 12 hours in advance.");
                      startTimeInput.value = ""; 
                  }
              }
          }

          if (startInput && stopInput) {
            startInput.addEventListener('change', function() {
                checkTime();
                stopInput.min = this.value;
                if (stopInput.value && stopInput.value < this.value) {
                    stopInput.value = "";
                }
            });
          }
          if (startTimeInput) {
            startTimeInput.addEventListener('change', checkTime);
            startTimeInput.addEventListener('blur', checkTime);
          }
      });
    </script>
    @include('partials.rate-modal-global')
  </body>
</html>
