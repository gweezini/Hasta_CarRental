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
        background-color: #f9fafb;
      }

      nav {
        background-color: #2d3748;
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


      /* Header/Search Form */
      .header-search {
         margin-top: 80px; 
         background-color: var(--white); 
         padding: 2rem; 
         border-bottom: 1px solid #eee;
      }
      .header-search form {
         max-width: var(--max-width);
         margin: auto;
         display: grid;
         grid-template-columns: repeat(3, 1fr);
         gap: 1.5rem;
         align-items: end;
      }
      .input__group { display: flex; flex-direction: column; gap: 8px; }
      .input__group label { font-weight: 600; font-size: 0.9rem; color: var(--text-dark); }
      .input__group input, .input__group select { width: 100%; padding: 10px; border: 1px solid var(--extra-light); border-radius: 5px; font-size: 1rem; color: var(--text-dark); outline: none; }
      .input__group select:invalid { color: var(--text-light); }
      .date-time-wrapper { display: flex; gap: 5px; }
      .date-time-wrapper input[type="date"] { flex: 2; }
      .date-time-wrapper select { flex: 1; }

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
      .footer__socials { display: flex; gap: 1rem; }
      .footer__socials a { padding: 8px 12px; font-size: 1.25rem; color: var(--text-light); border: 2px solid var(--text-light); border-radius: 100%; }
      .footer__socials a:hover { color: var(--primary-color); border-color: var(--primary-color); }

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
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ url('/#vehicles') }}">All Vehicles</a></li>
        <li><a href="#contact">Contact</a></li>
        
        @auth
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="mobile-only">
          <a href="{{ route('profile.edit', ['tab' => 'notifications']) }}">Notifications 
               @if(Auth::user()->unreadNotifications->count() > 0)
               ({{ Auth::user()->unreadNotifications->count() }})
               @endif
          </a>
        </li>
        <li class="mobile-only"><a href="{{ route('profile.edit') }}">Profile</a></li>
        <li class="mobile-only">
           <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button type="submit" style="background:none; border:none; padding:10px; width:100%; color:inherit; font:inherit; cursor:pointer; border: 1px solid white; border-radius: 5px; margin-top: 10px;">Logout</button>
           </form>
        </li>
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
          {{-- Authenticated Nav Items --}}
          <div x-data="{ open: false }" class="notif-wrapper">
             <button @click="open = !open" @click.away="open = false" class="notif-btn">
                <i class="ri-notification-3-line"></i>
                @if(Auth::user()->unreadNotifications->count() > 0) <span class="notif-badge"></span> @endif
             </button>
             <div x-show="open" style="display: none;" class="notif-dropdown" x-transition>
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
                                <i class="ri-notification-line"></i>
                            </div>
                            <div class="notif-content">
                                <p>{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div style="padding: 20px; text-align: center; color: #999; font-size: 0.85rem;">No notifications</div>
                    @endforelse
                </div>
                <div class="notif-footer"><a href="{{ route('profile.edit', ['tab' => 'notifications']) }}">View All Notifications</a></div>
             </div>
          </div>

          <div x-data="{ userOpen: false }" style="position: relative;">
              <button @click="userOpen = !userOpen" @click.away="userOpen = false" style="display: flex; align-items: center; gap: 0.5rem; background: none; border: none; cursor: pointer; outline: none;">
                  <img style="height: 36px; width: 36px; border-radius: 50%; object-fit: cover;" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ec5a29&color=fff" alt="Profile">
                  <span style="color: white; font-size: 0.875rem; font-weight: 500;" class="hidden md:block">{{ Auth::user()->name }}</span>
                  <i class="ri-arrow-down-s-line" style="color: white;"></i>
              </button>
              <div x-show="userOpen" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 12px; width: 12rem; background-color: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; z-index: 50; border: 1px solid #f3f4f6; padding: 4px 0;" x-transition>
                  <a href="{{ route('profile.edit') }}" style="display: block; padding: 8px 16px; font-size: 0.875rem; color: #374151; transition: background-color 0.2s;"> My Profile </a>
                  <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                      @csrf
                      <button type="submit" style="width: 100%; text-align: left; padding: 8px 16px; font-size: 0.875rem; color: #dc2626; background: none; border: none; font-weight: 500; cursor: pointer;"> Logout </button>
                  </form>
              </div>
          </div>
        @endguest
      </div>
    </nav>

    <!-- Header / Search Form -->
    <div class="header-search">
        @if(session('error'))
            <div class="max-w-4xl mx-auto mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm">
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
                        <option value="" disabled>Time</option>
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
                        <option value="" disabled>Time</option>
                        @for ($i = 420; $i < 1440; $i += 60)
                             @php $timeVal = sprintf('%02d:%02d', floor($i / 60), $i % 60); @endphp
                            <option value="{{ $timeVal }}" {{ $end && $end->format('H:i') == $timeVal ? 'selected' : '' }}>{{ $timeVal }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <button class="btn" style="height: 48px;">Update Search</button>
        </form>
    </div>

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
                        <p class="card__price">Starting from <strong>RM {{ $vehicle->price_per_hour }} / hour</strong></p>
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
        <h2 class="section__header" style="font-size: 2rem; margin-bottom: 2rem opacity: 0.7;">Unavailable Vehicles</h2>
        <p class="section__subheader">Currently booked for your selected time</p>

        @if($unavailableVehicles->isNotEmpty())
        <div class="range__grid">
            @foreach($unavailableVehicles as $vehicle)
            <div class="range__card unavailable">
                <img src="{{ asset('images/' . $vehicle->vehicle_image) }}" alt="{{ $vehicle->model }}" />
                <div class="range__details">
                    <h4 class="card__title">{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                    <p class="card__price">Starting from <strong>RM {{ $vehicle->price_per_hour }} / hour</strong></p>
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
                <p style="color: var(--text-light); line-height: 1.6; margin-bottom: 1rem;">Experience the freedom of the road with our premium car rental services.</p>
            </div>
            <div class="footer__col" id="contact">
                <h4>Follow Us</h4>
                <div class="footer__socials">
                    <a href="#"><i class="ri-facebook-fill"></i></a>
                    <a href="#"><i class="ri-instagram-fill"></i></a>
                    <a href="#"><i class="ri-twitter-fill"></i></a>
                </div>
            </div>
        </div>
        <div style="text-align: center; padding: 2rem; color: var(--text-light); background-color: #2d3748;">
            © 2026 Hasta Car Rental. All rights reserved.
        </div>
    </footer>

    <script>
      const menuBtn = document.getElementById("menu-btn");
      const navLinks = document.getElementById("nav-links");
      menuBtn.addEventListener("click", () => {
        navLinks.classList.toggle("open");
      });

      // Date constraints (Simple version)
      document.addEventListener("DOMContentLoaded", function() {
          const startInput = document.getElementById('start_date');
          const stopInput = document.getElementById('stop_date');
          startInput.addEventListener('change', function() {
            stopInput.min = this.value;
            if (stopInput.value && stopInput.value < this.value) {
                stopInput.value = "";
            }
          });
      });
    </script>
  </body>
</html>
