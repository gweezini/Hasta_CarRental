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
        background-color: #f9f9f9; /* Light background for edit page */
      }

      /* --- HEADER & BACKGROUND (Modified for Edit Page) --- */
      header {
        background-color: var(--text-dark);
        padding-bottom: 2rem;
      }

      /* --- NAVIGATION --- */
      nav {
        position: relative; /* Not fixed on this page */
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
        background-color: var(--text-dark);
      }

      .nav__header {
        display: flex;
        align-items: center;
        gap: 1rem;
      }

      .nav__logo img {
        width: 120px;
        height: auto;
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
    </style>
  </head>

  <body>
    <header>
      <nav>
        <div class="nav__header">
          <div class="nav__logo">
            <a href="{{ route('home') }}">
              <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta" />
            </a>
          </div>
        </div>
        <ul class="nav__links">
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('profile.edit') }}">My Profile</a></li>
        </ul>
      </nav>
    </header>

    <section class="section__container">
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
                <div class="input__group">
                  <label>Start Date</label>
                  <input
                    type="datetime-local"
                    name="start_time"
                    value="{{ old('start_time', \Carbon\Carbon::parse($booking->pickup_date_time)->format('Y-m-d\TH:i')) }}"
                    required
                  />
                </div>
                <div class="input__group">
                  <label>End Date</label>
                  <input
                    type="datetime-local"
                    name="end_time"
                    value="{{ old('end_time', \Carbon\Carbon::parse($booking->return_date_time)->format('Y-m-d\TH:i')) }}"
                    required
                  />
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

            <button type="submit" class="btn btn-primary" style="width: 100%;">
              Update Booking
            </button>

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
          <h3>Booking Summary</h3>
          <img src="{{ asset('images/' . $booking->vehicle->vehicle_image) }}" alt="Car" style="border-radius: 8px; margin-bottom: 1rem; width: 100%; object-fit: cover;" />
          
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span>Current Total</span>
            <strong>RM {{ number_format($booking->total_rental_fee, 2) }}</strong>
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
              <div style="display: flex; justify-content: space-between; font-size: 1.2rem; color: var(--primary-color); margin-top: 0.5rem;">
                <span>New Estimated Total</span>
                <strong id="summary-total">-</strong>
             </div>
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
        handlePickupChange();
        handleDropoffChange(); // Ensure dropoff UI is correct based on value
        handleSameLocationChange();
        
        // --- LIVE PRICE UPDATE ---
             const startTimeInput = document.querySelector('input[name="start_time"]');
             const endTimeInput = document.querySelector('input[name="end_time"]');
             const pickupSelect = document.getElementById('pickup_location');
             const dropoffSelect = document.getElementById('dropoff_location'); 
             const sameLocCheckbox = document.getElementById('same_location_checkbox');

             // Set Min Start Date to NOW (Relaxed constraint for modification)
             const now = new Date();
             const minStart = now.toISOString().slice(0, 16);
             startTimeInput.setAttribute("min", minStart);

             startTimeInput.addEventListener("change", function() {
                if(this.value) {
                    const start = new Date(this.value);
                    const minEnd = new Date(start.getTime() + 60 * 60 * 1000); // +1 Hour
                    endTimeInput.setAttribute("min", minEnd.toISOString().slice(0, 16));
                    
                    if(endTimeInput.value && new Date(endTimeInput.value) < minEnd) {
                        endTimeInput.value = "";
                        alert("End time has been reset because it must be at least 1 hour after start time.");
                    }
                    calculatePrice();
                }
             });

             endTimeInput.addEventListener("change", calculatePrice);
             pickupSelect.addEventListener("change", calculatePrice);
             dropoffSelect.addEventListener("change", calculatePrice);
             sameLocCheckbox.addEventListener("change", calculatePrice);
             
             // Initial Calc
             if(startTimeInput.value && endTimeInput.value) calculatePrice();
             
             function calculatePrice() {
                const vehicleId = document.querySelector('input[name="vehicle_id"]').value;
                const start = startTimeInput.value;
                const end = endTimeInput.value;
                const pickup = pickupSelect.value;
                let dropoff = pickup;
                
                const isSame = sameLocCheckbox.checked;
                if(!isSame) {
                     if(dropoffSelect) dropoff = dropoffSelect.value;
                }
                
                // For Edit, we pass the existing voucher ID embedded in the booking if we want to preserve it.
                // Or we can just let backend handle it? 
                // The API expects 'selected_voucher_id'. 
                // We'll output a hidden field with the current booking's voucher id
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
                    
                    document.getElementById('summary-hours').innerText = data.hours + " Hours";
                    document.getElementById('summary-subtotal').innerText = "RM " + data.subtotal;
                    document.getElementById('summary-delivery').innerText = "RM " + data.delivery_fee;
                    document.getElementById('summary-discount').innerText = "- RM " + data.discount;
                    document.getElementById('summary-total').innerText = "RM " + data.total;
                });
             }
      });
    </script>
  </body>
</html>
