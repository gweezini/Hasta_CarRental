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
        background-color: #2d3748;
        padding-top: 5rem;
      }

      .footer__container {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 2rem;
        border-bottom: 1px solid var(--text-light);
        padding-bottom: 2rem;
      }

      .footer__col h4 {
        margin-bottom: 2rem;
        font-size: 1.2rem;
        font-family: var(--header-font);
        color: var(--white);
        text-transform: uppercase;
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
          <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li><a href="{{ url('/#vehicles') }}">Vehicles</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="#about">About Us</a></li>
          <li><a href="{{ route('profile.edit') }}">My Profile</a></li>

          <li class="mobile-only">
            <a href="{{ route('profile.edit', ['tab' => 'notifications']) }}">Notifications 
                 @if(Auth::user()->unreadNotifications->count() > 0)
                 ({{ Auth::user()->unreadNotifications->count() }})
                 @endif
            </a>
          </li>

          <li class="mobile-only">
            <a href="{{ route('profile.edit') }}">Profile</a>
          </li>

          <li class="mobile-only">
             <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" style="background:none; border:none; padding:10px; width:100%; color:inherit; font:inherit; cursor:pointer; border: 1px solid white; border-radius: 5px; margin-top: 10px;">
                    Logout
                </button>
             </form>
          </li>
        </ul>

        <div class="nav__btn">
          
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

        </div>
      </nav>
      
      <div class="header__container" id="home">
        <h3 style="color: #ffffff !important; font-size: 50px; font-weight: 800; margin-bottom: 20px;">
          Welcome back, {{ Auth::user()->name }}
        </h3>
        <h1>AFFORDABLE CAR RENTAL SERVICE</h1>
        <form action="/">
          <div class="input__group">
            <label for="pickup">Pick up location</label>
            <input
              type="text"
              id="pickup"
              placeholder="Student Mall, UTM"
              required
            />
          </div>

      <div class="input__group">
        <label for="start">Start Date & Time</label>
          <div class="date-time-wrapper">
            <input type="date" name="start_date" id="start_date" min="{{ date('Y-m-d') }}" required />
                <select name="start_time" style="flex: 1; padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 1rem; color: #737373; outline: none;" required>
                    <option value="" disabled selected>Select Time</option>
                    @for ($i = 420; $i < 1440; $i += 10)
                        @php $timeVal = sprintf('%02d:%02d', floor($i / 60), $i % 60); @endphp
                        <option value="{{ $timeVal }}">{{ $timeVal }}</option>
                    @endfor
                </select>
          </div>
      </div>
      
      <div class="input__group">
        <label for="stop">End Date & Time</label>
          <div class="date-time-wrapper">
            <input type="date" name="stop_date" id="stop_date" min="{{ date('Y-m-d') }}" required />
                <select name="stop_time" style="flex: 1; padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 1rem; color: #737373; outline: none;" required>
                    <option value="" disabled selected>Select Time</option>
                    @for ($i = 420; $i < 1440; $i += 60)
                        @php $timeVal = sprintf('%02d:%02d', floor($i / 60), $i % 60); @endphp
                        <option value="{{ $timeVal }}">{{ $timeVal }}</option>
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

          <button class="btn">
            <i class="ri-search-line"></i>
          </button>
        </form>
      </div>
    </header>

    
    <section class="section__container range__container" id="vehicles">
      <h2 class="section__header">OUR RANGE OF VEHICLES</h2>

      <div class="range__grid">
        @foreach($vehicles as $vehicle)
        <div
          class="range__card {{ $vehicle->status !== 'Available' ? 'unavailable' : '' }}"
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

            <div class="card__btn" style="background-color: transparent;">
            @if($vehicle->status === 'Available')
                @auth
                    <a href="{{ route('booking.show', [
                    'id' => $vehicle->id, 
                    'start_date' => request('start_date'), 
                    'start_time' => request('start_time'), 
                    'stop_date' => request('stop_date'), 
                    'stop_time' => request('stop_time')
                ]) }}" class="btn" style="background-color: var(--primary-color); color: white; width: 100%; display: block; text-align: center; text-decoration: none;">
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
      </div>
    </section>

    <br /><br />

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
            <li><a href="#about">About Us</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms & Conditions</a></li>
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
          const startD = document.getElementById('start_date');
          const startT = document.querySelector('select[name="start_time"]');
          const endD = document.getElementById('stop_date');
          const endT = document.querySelector('select[name="stop_time"]');

          function regenerateOpts() {
              if (!startT || !endT) return;
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
              updateConstraints();
          }

          function updateConstraints() {
              if(!startD || !startT || !endD || !endT) return;

              const sDate = startD.value;
              const sTime = startT.value;
              const eDate = endD.value;

              // 1. Min Date
              if (sDate) {
                  endD.min = sDate;
                  if (eDate && eDate < sDate) {
                      endD.value = sDate;
                  }
              }

              // 2. Min Time
              if (sDate && endD.value && sDate === endD.value && sTime) {
                  const [h, m] = sTime.split(':').map(Number);
                  const minMinutes = (h * 60 + m) + 60; // +1 Hour

                  Array.from(endT.options).forEach(opt => {
                      if (opt.value) {
                          const [oh, om] = opt.value.split(':').map(Number);
                          const oMin = oh * 60 + om;
                          if (oMin < minMinutes) {
                              opt.disabled = true;
                              opt.style.color = '#ccc';
                          } else {
                              opt.disabled = false;
                              opt.style.color = '';
                          }
                      }
                  });

                  if (endT.value) {
                      const [th, tm] = endT.value.split(':').map(Number);
                      if ((th * 60 + tm) < minMinutes) endT.value = "";
                  }

              } else {
                  Array.from(endT.options).forEach(opt => {
                      if(opt.value) {
                          opt.disabled = false;
                          opt.style.color = '';
                      }
                  });
              }
          }

          if(startD) startD.addEventListener('change', updateConstraints);
          if(startT) {
              startT.addEventListener('change', regenerateOpts);
          }
          if(endD) endD.addEventListener('change', updateConstraints);

          // Init
          if(startT && startT.value) regenerateOpts();
          else updateConstraints();
      });
    </script>
  </body>
</html>