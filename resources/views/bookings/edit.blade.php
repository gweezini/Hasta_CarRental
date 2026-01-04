<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modify Booking - Hasta Car Rental</title>
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ec5a29',
                    }
                }
            }
        }
    </script>
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

      /* Specific Button Styles */
      .btn-transparent {
        background: transparent;
        color: var(--white);
        border: 1px solid var(--white);
      }

      .btn-primary {
        background-color: var(--primary-color);
        color: var(--white);
      }

      /* img { display: block; width: 100%; }  -- Removed to prevent conflict with Navbar icons */

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
        background-color: #f9f9f9; /* Light background for edit page */
      }

      /* Removed legacy nav styles */

      /* Booking Grid */
      .booking__grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        align-items: flex-start;
        margin-top: 2rem;
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
        top: 20px;
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

      .input__group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 1rem;
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
        color: #111;
        outline: none;
      }

      /* Responsive */
      @media (max-width: 992px) {
        .booking__grid {
          grid-template-columns: 1fr;
        }
        .booking__summary {
            position: static;
            margin-top: 2rem;
            background-color: #fff8f5;
            border: 2px solid #ec5a29;
        }
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
    </style>
  </head>

  <body>
    <nav class="fixed top-0 left-0 w-full z-50 bg-[#2d3748] shadow-md flex justify-between items-center px-8 py-4" id="navbar">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta Logo" class="h-10 w-auto rounded-md">
            </a>
        </div>

                <!-- Desktop Menu -->
                <!-- Navigation Links (Center) -->
                <ul class="hidden md:flex items-center gap-8 text-white font-medium"> <!-- Using UL to match Profile Page structure exactly -->
                    <li><a href="{{ route('dashboard') }}" class="hover:text-[#ec5a29] transition text-sm">Dashboard</a></li>
                    <li><a href="{{ url('/#vehicles') }}" class="hover:text-[#ec5a29] transition text-sm">Vehicles</a></li>
                    <li><a href="{{ url('/#contact') }}" class="hover:text-[#ec5a29] transition text-sm">Contact</a></li>
                    <li><a href="{{ url('/#about') }}" class="hover:text-[#ec5a29] transition text-sm">About Us</a></li>
                </ul>

                <!-- Icons (Right) -->
                <div class="hidden md:flex items-center space-x-8">

                    <!-- Notification Bell -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false" class="relative text-gray-300 hover:text-white transition focus:outline-none mt-1">
                            <i class="ri-notification-3-line text-xl"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute -top-1 -right-1 bg-[#ec5a29] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>

                        <div x-show="open" style="display: none;" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl overflow-hidden z-50 border border-gray-100 origin-top-right">
                            
                            <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Notifications</h3>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-[#ec5a29] hover:underline font-medium">Mark all read</button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                @forelse(auth()->user()->notifications as $notification)
                                    <div class="flex items-start gap-3 p-3 border-b border-gray-50 hover:bg-gray-50 transition cursor-default {{ $notification->read_at ? 'opacity-60' : '' }}">
                                        <div class="flex-shrink-0 mt-1">
                                            @if($notification->type == 'App\Notifications\BookingStatusUpdated')
                                                <div class="w-6 h-6 rounded-full bg-orange-100 text-[#ec5a29] flex items-center justify-center"><i class="ri-car-line text-xs"></i></div>
                                            @elseif($notification->type == 'App\Notifications\PaymentStatusUpdated')
                                                <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><i class="ri-money-dollar-circle-line text-xs"></i></div>
                                            @else
                                                <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="ri-notification-line text-xs"></i></div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 leading-tight">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                            <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-center text-xs text-gray-400">No notifications</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div x-data="{ userOpen: false }" class="relative">
                        <button @click="userOpen = !userOpen" @click.away="userOpen = false" class="flex items-center gap-2 group focus:outline-none">
                            <img class="h-9 w-9 rounded-full object-cover border-2 border-transparent group-hover:border-[#ec5a29] transition" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ec5a29&color=fff" 
                                 alt="Profile">
                            <span class="text-white text-sm font-medium hidden md:block group-hover:text-[#ec5a29] transition">{{ Auth::user()->name }}</span>
                            <i class="ri-arrow-down-s-line text-gray-400 group-hover:text-[#ec5a29] transition"></i>
                        </button>

                        <div x-show="userOpen" style="display: none;"
                             x-transition.origin.top.right
                             class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl overflow-hidden z-50 border border-gray-100 py-1">
                            
                            <div class="px-4 py-3 border-b border-gray-50">
                                <p class="text-xs text-gray-500">Signed in as</p>
                                <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#ec5a29]">
                                <i class="ri-home-line mr-2 align-middle"></i> Home
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">
                                    <i class="ri-logout-box-r-line mr-2 align-middle"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="menu-btn" class="text-white hover:text-[#ec5a29] focus:outline-none">
                        <i class="ri-menu-3-line text-2xl"></i>
                    </button>
                </div>
      </nav>

    <section class="section__container" style="padding-top: 8rem;">
      <h2 class="section__header" style="text-align: left; font-size: 2.5rem; margin-bottom: 1rem;">
        Modify Booking #{{ $booking->id }}
      </h2>
      <p style="margin-bottom: 2rem; color: var(--text-light);">
          You can modify your booking details below. Please note that changing dates or locations may affect the total rental fee.
      </p>

      <div class="booking__grid">
        <div class="booking__form">
          <form
            id="bookingForm"
            action="{{ route('booking.update', $booking->id) }}"
            method="POST"
            enctype="multipart/form-data"
          >
            @csrf
            @method('PUT')
            <input type="hidden" name="vehicle_id" value="{{ $booking->vehicle_id }}" />

            <div class="form__section">
              <h3><i class="ri-calendar-check-line"></i> Rental Period</h3>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <!-- Start Date & Time -->
                <div class="input__group">
                  <label>Start Date</label>
                  <div class="date-time-wrapper" style="display: flex; gap: 5px;">
                        <input 
                            type="date" 
                            id="start_date_visual" 
                            required 
                            min="{{ date('Y-m-d') }}" 
                            style="flex: 1;"
                            value="{{ old('start_date', \Carbon\Carbon::parse($booking->pickup_date_time)->format('Y-m-d')) }}"
                        />
                        <select id="start_time_visual" required style="padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px; flex: 1;">
                            <option value="" disabled>Select Time</option>
                            @php 
                                $currentStart = \Carbon\Carbon::parse($booking->pickup_date_time)->format('H:i'); 
                            @endphp
                            @for($i=420; $i<1440; $i+=10)
                                @php $t = sprintf('%02d:%02d', floor($i/60), $i%60); @endphp
                                <option value="{{ $t }}" {{ $currentStart == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                        <input type="hidden" name="start_time" id="start_time_hidden" value="{{ \Carbon\Carbon::parse($booking->pickup_date_time)->format('Y-m-d\TH:i') }}">
                  </div>
                </div>

                <!-- End Date & Time -->
                <div class="input__group">
                  <label>End Date</label>
                  <div class="date-time-wrapper" style="display: flex; gap: 5px;">
                        <input 
                            type="date" 
                            id="end_date_visual" 
                            required 
                            min="{{ date('Y-m-d') }}" 
                            style="flex: 1;"
                            value="{{ old('end_date', \Carbon\Carbon::parse($booking->return_date_time)->format('Y-m-d')) }}"
                        />
                        <select id="end_time_visual" required style="padding: 10px; border: 1px solid #e5e5e5; border-radius: 5px; flex: 1;">
                            <option value="" disabled>Select Time</option>
                            @php 
                                $currentEnd = \Carbon\Carbon::parse($booking->return_date_time)->format('H:i'); 
                            @endphp
                            @for($i=420; $i<1440; $i+=60)
                                @php $t = sprintf('%02d:%02d', floor($i/60), $i%60); @endphp
                                <option value="{{ $t }}" {{ $currentEnd == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                        <input type="hidden" name="end_time" id="end_time_hidden" value="{{ \Carbon\Carbon::parse($booking->return_date_time)->format('Y-m-d\TH:i') }}">
                  </div>
                </div>
              </div>
            </div>

            <div class="form__section">
              <h3><i class="ri-map-pin-line"></i> Pickup & Dropoff</h3>
              
              <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                <div class="input__group">
                  <label>Pickup Location</label>
                  <select
                    name="pickup_location"
                    id="pickup_location"
                    onchange="handlePickupChange()"
                    required
                  >
                    <option value="office" {{ old('pickup_location', $booking->pickup_location) == 'office' ? 'selected' : '' }}>Student Mall (Free)</option>
                    <option value="campus" {{ old('pickup_location', $booking->pickup_location) == 'campus' ? 'selected' : '' }}>In Campus (+RM 2.50)</option>
                    <option value="taman_u" {{ old('pickup_location', $booking->pickup_location) == 'taman_u' ? 'selected' : '' }}>Taman Universiti (+RM 7.50)</option>
                    <option value="jb" {{ old('pickup_location', $booking->pickup_location) == 'jb' ? 'selected' : '' }}>Other Area JB (+RM 25)</option>
                  </select>
                </div>

                <div class="input__group" id="custom_pickup_group" style="display: none;">
                  <label>Specific Pickup Address</label>
                  <input
                    type="text"
                    name="custom_pickup_address"
                    id="custom_pickup_address"
                    value="{{ old('custom_pickup_address', $booking->custom_pickup_address) }}"
                    placeholder="Enter your specific pickup address"
                  />
                </div>

                <div style="display: flex; align-items: center; gap: 10px; margin: 1rem 0;">
                  <input
                    type="checkbox"
                    id="same_location_checkbox"
                    name="same_location_checkbox"
                    {{ old('same_location_checkbox', $booking->pickup_location == $booking->dropoff_location) ? 'checked' : '' }}
                    onchange="handleSameLocationChange()"
                  />
                  <label for="same_location_checkbox" style="margin: 0; cursor: pointer;">Drop off location same as pick up location</label>
                </div>

                <input type="hidden" name="dropoff_location" id="dropoff_location_hidden" value="{{ $booking->dropoff_location }}"/>

                <div id="dropoff_section" style="display: none;">
                  <div class="input__group">
                    <label>Dropoff Location</label>
                    <select
                      name="dropoff_location_select"
                      id="dropoff_location"
                      onchange="handleDropoffChange()"
                    >
                      <option value="">-- Select Dropoff Location --</option>
                      <option value="office" {{ old('dropoff_location', $booking->dropoff_location) == 'office' ? 'selected' : '' }}>Student Mall (Free)</option>
                      <option value="campus" {{ old('dropoff_location', $booking->dropoff_location) == 'campus' ? 'selected' : '' }}>In Campus (+RM 2.50)</option>
                      <option value="taman_u" {{ old('dropoff_location', $booking->dropoff_location) == 'taman_u' ? 'selected' : '' }}>Taman Universiti (+RM 7.50)</option>
                      <option value="jb" {{ old('dropoff_location', $booking->dropoff_location) == 'jb' ? 'selected' : '' }}>Other Area JB (+RM 25)</option>
                    </select>
                  </div>

                  <div class="input__group" id="custom_dropoff_group" style="display: none;">
                    <label>Specific Dropoff Address</label>
                    <input
                      type="text"
                      name="custom_dropoff_address"
                      id="custom_dropoff_address"
                      value="{{ old('custom_dropoff_address', $booking->custom_dropoff_address) }}"
                      placeholder="Enter your specific dropoff address"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div class="form__section">
              <h3><i class="ri-user-line"></i> Personal Information</h3>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="input__group">
                  <label>Full Name</label>
                  <input type="text" name="name" value="{{ old('name', $booking->customer_name) }}" required />
                </div>
                <div class="input__group">
                  <label>Phone Number</label>
                  <input type="tel" name="phone" value="{{ old('phone', $booking->customer_phone) }}" required />
                </div>
              </div>
            </div>

            <div class="form__section">
                <h3><i class="ri-alarm-warning-line"></i> Emergency Contact</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                  <div class="input__group">
                    <label>Contact Name</label>
                    <input type="text" name="emergency_name" value="{{ old('emergency_name', $booking->emergency_contact_name) }}" required />
                  </div>
                  <div class="input__group">
                    <label>Contact Number</label>
                    <input type="tel" name="emergency_contact" value="{{ old('emergency_contact', $booking->emergency_contact_phone) }}" required />
                  </div>
                </div>
              </div>



            <div class="form__section">
              <h3><i class="ri-secure-payment-line"></i> Refund Details</h3>
              <p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 1rem;">
                  <i class="ri-information-line"></i> Please ensure your bank details are correct for any potential refunds.
              </p>
              <div class="refund-box">
                  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                      <div class="input__group">
                          <label>Bank Name</label>
                          <div class="input-with-icon">
                              <i class="ri-bank-line"></i>
                              <input type="text" name="refund_bank_name" value="{{ old('refund_bank_name', $booking->refund_bank_name) }}" placeholder="e.g. Maybank" required />
                          </div>
                      </div>
                      <div class="input__group">
                          <label>Account Number</label>
                          <div class="input-with-icon">
                              <i class="ri-hashtag"></i>
                              <input type="text" name="refund_account_number" value="{{ old('refund_account_number', $booking->refund_account_number) }}" placeholder="e.g. 1122334455" required />
                          </div>
                      </div>
                      <div class="input__group" style="grid-column: 1 / -1;">
                          <label>Recipient Name</label>
                          <div class="input-with-icon">
                              <i class="ri-user-star-line"></i>
                              <input type="text" name="refund_recipient_name" value="{{ old('refund_recipient_name', $booking->refund_recipient_name) }}" placeholder="Name as per bank account" required />
                          </div>
                      </div>
                  </div>
              </div>
            </div>

            <!-- PAYMENT & REFUND SECTIONS -->
            <input type="hidden" id="original_grand_total" value="{{ $booking->total_rental_fee + $booking->deposit_amount }}">

            <!-- Payment Section (Hidden by default) -->
            <div id="payment-section" class="form__section" style="display: none; background: #fff8f5; padding: 20px; border: 2px solid #ec5a29; border-radius: 10px; margin-top: 2rem;">
                <h3 style="color: #d14a1e;"><i class="ri-money-dollar-circle-line"></i> Additional Payment Required</h3>
                <p style="margin-bottom: 1rem;">The new total is higher than your previous booking. Please pay the difference.</p>
                
                <div style="font-size: 1.2rem; font-weight: bold; margin-bottom: 1rem; color: #d14a1e;">
                    Top-up Amount: <span id="payment-diff-amount">RM 0.00</span>
                </div>

                <!-- QR Code / Bank Details -->
                <div style="text-align: left; margin-bottom: 1rem;">
                     <img src="{{ asset('images/paymentqr.png') }}" style="max-width: 150px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                     <p style="margin-top: 5px; font-weight: 600;">DuitNow QR / CIMB 8600123456</p>
                </div>

                <div class="input__group">
                    <label>Upload Payment Receipt <span style="color: red;">*</span></label>
                    <input type="file" name="receipt_image" id="receipt_image_input" accept="image/*, .pdf">
                </div>
            </div>

            <!-- Refund Section (Hidden by default) -->
            <div id="refund-section" class="form__section" style="display: none; background: #e8f5e9; padding: 20px; border: 2px solid #4caf50; border-radius: 10px; margin-top: 2rem;">
                <h3 style="color: #2e7d32;"><i class="ri-refund-2-line"></i> Refund Information</h3>
                <p>The new total is lower or equal. No additional payment is needed.</p>
                <div style="font-size: 1.1rem; font-weight: bold; margin: 1rem 0; color: #2e7d32;">
                    Refund Amount: <span id="refund-diff-amount">RM 0.00</span>
                </div>
                <p style="font-size: 0.9rem; color: #1b5e20;">
                    If there is remaining money, it will be returned within 5 working days to the customer's bank account.
                </p>
            </div>

            @if ($errors->any())
            <div style="background-color: #ffe6e6; border: 1px solid #d93025; color: #d93025; padding: 1rem; border-radius: 8px; margin-top: 1.5rem;">
              <h4 style="margin: 0 0 0.5rem 0; font-weight: bold;">
                <i class="ri-error-warning-line"></i> Please fix the following:
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
          <h3 style="font-weight: 800;">Booking Summary</h3>
          <img src="{{ asset('images/' . $booking->vehicle->vehicle_image) }}" alt="Car" style="border-radius: 8px; margin-bottom: 0.5rem; width: 100%; object-fit: cover;" />
          
          <h4 style="margin-top: 0.5rem; font-size: 1.1rem; font-weight: bold; color: var(--text-dark);">
              {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}
          </h4>
          <p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 1rem;">
              {{ $booking->vehicle->plate_number }}
          </p>
          
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span>Current Total (Inc. Deposit)</span>
            <strong>RM {{ number_format($booking->total_rental_fee + $booking->deposit_amount, 2) }}</strong>
          </div>
          
          <hr style="margin: 1rem 0; opacity: 0.2;" />
              Note: The total price will be recalculated upon update based on the new dates and location.
          </p>
          
          <div style="margin-top: 1rem; border-top: 1px dashed #ccc; pt-2;">
             <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Duration</span>
                <strong id="summary-hours">-</strong>
             </div>
             <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>New Subtotal</span>
                <strong id="summary-subtotal">-</strong>
             </div>
             <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--primary-color);">
                <span>Delivery Fee</span>
                <strong id="summary-delivery">-</strong>
             </div>
             <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Discount (Orig)</span>
                <strong id="summary-discount">-</strong>
             </div>
             <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Security Deposit</span>
                <strong id="summary-deposit">-</strong>
             </div>
              <div style="display: flex; justify-content: space-between; font-size: 1.2rem; color: var(--primary-color); margin-top: 0.5rem;">
                <span>New Estimated Total</span>
                <strong id="summary-total">-</strong>
             </div>
             
             <!-- Moved generic submit button here -->
              <button 
                type="submit" 
                form="bookingForm" 
                class="btn btn-primary" 
                style="width: 100%; margin-top: 1.5rem; font-size: 1.2rem; padding: 15px;"
              >
                  Update Booking <i class="ri-arrow-right-line"></i>
              </button>
          </div>
        </div>
      </div>
    </section>

    <script>
      function handlePickupChange() {
        const pickupValue = document.getElementById("pickup_location").value;
        const customPickupGroup = document.getElementById("custom_pickup_group");
        const customPickupInput = document.getElementById("custom_pickup_address");

        if (pickupValue !== "office") {
          customPickupGroup.style.display = "block";
          customPickupInput.setAttribute("required", "required");
        } else {
          customPickupGroup.style.display = "none";
          customPickupInput.removeAttribute("required");
        }
      }

      function handleDropoffChange() {
        const dropoffValue = document.getElementById("dropoff_location").value;
        const dropoffHidden = document.getElementById("dropoff_location_hidden");
        const customDropoffGroup = document.getElementById("custom_dropoff_group");
        const customDropoffInput = document.getElementById("custom_dropoff_address");

        dropoffHidden.value = dropoffValue;

        if (dropoffValue !== "office" && dropoffValue !== "") {
          customDropoffGroup.style.display = "block";
          customDropoffInput.setAttribute("required", "required");
        } else {
          customDropoffGroup.style.display = "none";
          customDropoffInput.removeAttribute("required");
        }
      }

      function handleSameLocationChange() {
        const checkbox = document.getElementById("same_location_checkbox");
        const dropoffSection = document.getElementById("dropoff_section");
        const dropoffSelect = document.getElementById("dropoff_location");
        const dropoffHidden = document.getElementById("dropoff_location_hidden");
        const pickupValue = document.getElementById("pickup_location").value;

        if (checkbox.checked) {
          dropoffSection.style.display = "none";
          dropoffSelect.removeAttribute("required");
          dropoffSelect.removeAttribute("name"); // Don't submit this select
          dropoffHidden.value = pickupValue;
          document.getElementById("custom_dropoff_group").style.display = "none";
        } else {
          dropoffSection.style.display = "block";
          dropoffSelect.setAttribute("required", "required");
          dropoffSelect.setAttribute("name", "dropoff_location"); // Re-enable name
          dropoffHidden.value = dropoffSelect.value || "";
        }
      }

      document.addEventListener("DOMContentLoaded", function () {
        // --- 1. SETUP VARIABLES ---
        
        // Date/Time Inputs
        const startD = document.getElementById('start_date_visual');
        const startT = document.getElementById('start_time_visual');
        const startHidden = document.getElementById('start_time_hidden');
        
        const endD = document.getElementById('end_date_visual');
        const endT = document.getElementById('end_time_visual');
        const endHidden = document.getElementById('end_time_hidden');

        // Location & Other
        const pickupSelect = document.getElementById('pickup_location');
        const dropoffSelect = document.getElementById('dropoff_location');
        const sameLocCheckbox = document.getElementById('same_location_checkbox');
        const vehicleId = document.querySelector('input[name="vehicle_id"]').value;
        const currentBookingId = "{{ $booking->id }}";

        let bookedRanges = []; // Store booked slots

        // --- 2. CORE LOGIC FUNCTIONS ---

        // Sync visual inputs to hidden inputs for submission and calculation
        function syncHiddenInputs() {
            if(startD.value && startT.value) {
                startHidden.value = startD.value + 'T' + startT.value;
            } else {
                startHidden.value = '';
            }

            if(endD.value && endT.value) {
                endHidden.value = endD.value + 'T' + endT.value;
            } else {
                endHidden.value = '';
            }
        }

        function fetchAvailability() {
            // Include exclude_booking_id to allow self-overlap (editing own booking)
            fetch(`/booking/vehicle/${vehicleId}/availability?exclude_booking_id=${currentBookingId}`)
                .then(res => res.json())
                .then(data => {
                    bookedRanges = data.active_bookings.map(r => ({
                        start: new Date(r.start),
                        end: new Date(r.end)
                    }));
                    updateAvailabilityUI(); // Re-check UI after fetching
                });
        }

        function regenerateEndOptions() {
            if(!startT || !endT) return;
            const sTime = startT.value; 
            let mins = "00";
            if (sTime) {
                const p = sTime.split(':');
                if (p.length === 2) mins = p[1];
            }

            // Preserve hour choice if possible
            const curVal = endT.value;
            let curHour = -1;
            if (curVal) curHour = parseInt(curVal.split(':')[0]);

            // Rebuild options (07:00 to 23:00)
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

            // Strict Overlap Helper: startA < endB && endA > startB
            const isStrictOverlapping = (startA, endA, startB, endB) => {
                return startA < endB && endA > startB;
            };

            // 1. Min Date Constraints
            if (selectedDate) {
                endD.min = selectedDate;
                if (endDate && endDate < selectedDate) {
                    endD.value = selectedDate;
                }
            }

            // 2. Start Time Availability
            if (selectedDate) {
                Array.from(startT.options).forEach(opt => {
                    const time = opt.value;
                    if(!time) return;
                    
                    const myStart = new Date(selectedDate + 'T' + time);
                    const myMinEnd = new Date(myStart.getTime() + 60 * 60 * 1000); // +1 Hour min duration
                    
                    const isBooked = bookedRanges.some(r => isStrictOverlapping(myStart, myMinEnd, r.start, r.end));

                    if (isBooked) {
                        opt.disabled = true;
                        opt.style.color = '#ccc';
                        opt.text = time + " (Unavailable)";
                    } else {
                        opt.disabled = false;
                        opt.style.color = '';
                        // Restore text if it was changed
                        if(opt.text.includes('Unavailable')) opt.text = time;
                    }
                });
            }
            
            // 3. End Time Logic (Availability AND Min 1 Hour Duration)
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
                     if(!time) return;

                     // A. Check Availability [currentStart, currentEnd]
                     let isBooked = false;
                     let isSlotBooked = false;
                     
                     if (currentStart) {
                         const currentEnd = new Date(endDate + 'T' + time);
                         isBooked = bookedRanges.some(r => isStrictOverlapping(currentStart, currentEnd, r.start, r.end));
                     } else {
                         // Loose check if strict unavailable
                         const pt = new Date(endDate + 'T' + time);
                         isSlotBooked = bookedRanges.some(r => pt >= r.start && pt < r.end); 
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
                         if(opt.text.includes('Booked')) opt.text = time;
                     }
                 });
                 
                 // If current selection became invalid, clear it
                 if(endT.value) {
                     const selectedOpt = endT.options[endT.selectedIndex];
                     if(selectedOpt && selectedOpt.disabled) endT.value = "";
                 }
            }
            
            syncHiddenInputs();
            if(startHidden.value && endHidden.value) calculatePrice();
        }

        // --- 3. PRICE CALCULATION ---
        function calculatePrice() {
            const start = startHidden.value;
            const end = endHidden.value;
            const pickup = pickupSelect.value;
            let dropoff = pickup;
            
            if(!sameLocCheckbox.checked) {
                 if(dropoffSelect) dropoff = dropoffSelect.value;
            }
            
            const voucherId = "{{ $booking->voucher_id ?? '' }}"; 

            if(!start || !end) return;

            fetch('{{ route("booking.calculate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    vehicle_id: vehicleId,
                    start_time: start,
                    end_time: end,
                    pickup_location: pickup,
                    dropoff_location: dropoff,
                    selected_voucher_id: voucherId,
                    manual_code: "{{ $booking->promo_code ?? '' }}"
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.error) return;
                
                const update = (id, val) => { const el = document.getElementById(id); if(el) el.innerText = val; };
                update('summary-hours', data.hours + " Hours");
                update('summary-subtotal', "RM " + data.subtotal);
                update('summary-delivery', "RM " + data.delivery_fee);
                update('summary-discount', "- RM " + data.discount);
                update('summary-deposit', "RM " + data.deposit);
                update('summary-total', "RM " + data.grand_total);

                // --- CHECK PRICE DIFF ---
                const newTotal = parseFloat((data.grand_total + "").replace(/,/g, ''));
                const originalTotal = parseFloat(document.getElementById('original_grand_total').value);
                const diff = newTotal - originalTotal;
                
                const paySec = document.getElementById('payment-section');
                const refSec = document.getElementById('refund-section');
                const fileInput = document.getElementById('receipt_image_input');
                
                if (diff > 0) {
                    paySec.style.display = 'block';
                    refSec.style.display = 'none';
                    
                    document.getElementById('payment-diff-amount').innerText = "RM " + diff.toFixed(2);
                    if(fileInput) fileInput.setAttribute('required', 'required');
                } else {
                    paySec.style.display = 'none';
                    refSec.style.display = 'block';
                    
                    const refundAmt = Math.abs(diff);
                    document.getElementById('refund-diff-amount').innerText = "RM " + refundAmt.toFixed(2);
                    if(fileInput) fileInput.removeAttribute('required');
                }
            });
        }

        // --- 4. EVENT LISTENERS ---
        // Date/Time
        startD.addEventListener('change', updateAvailabilityUI);
        startT.addEventListener('change', function() {
            regenerateEndOptions(); // Changing start time might shift end mins or invalidate end slots
        });
        endD.addEventListener('change', updateAvailabilityUI);
        endT.addEventListener('change', function() {
            updateAvailabilityUI();
            calculatePrice();
        });

        // Location
        pickupSelect.addEventListener("change", function() {
            handlePickupChange();
            calculatePrice();
        });
        if(dropoffSelect) {
            dropoffSelect.addEventListener("change", function() {
                handleDropoffChange();
                calculatePrice();
            });
        }
        sameLocCheckbox.addEventListener("change", function() {
            handleSameLocationChange();
            calculatePrice();
        });

        // --- 5. INITIALIZATION ---
        handlePickupChange();
        handleDropoffChange();
        handleSameLocationChange();
        fetchAvailability(); // Load booked slots
        
        // Initial Calculation
        updateAvailabilityUI();
        
      });
    </script>
  </body>
</html>
