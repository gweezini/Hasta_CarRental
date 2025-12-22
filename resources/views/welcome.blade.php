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
        --primary-color: #ec5a29; /* Updated theme color */
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

      .section__header {
        font-size: 3.25rem;
        font-weight: 700;
        font-family: var(--header-font);
        color: var(--text-dark);
        letter-spacing: -5px;
        line-height: 4.25rem;
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
      }

      .btn:hover {
        color: var(--white);
        background-color: var(--primary-color);
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
        background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4)),
          url("{{ asset('images/hastabackground.png') }}");
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
        min-height: 100vh;
      }

      /* --- DYNAMIC NAVIGATION --- */
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

      /* --- HERO CONTAINER & FORM FIX --- */
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

      .checkbox_group {
        grid-column: 1 / span 2;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
      }

      .checkbox_group input {
        width: 18px;
        height: 18px;
        cursor: pointer;
      }

      .checkbox_group label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
        cursor: pointer;
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

      /* --- RANGE SECTION --- */
      .range__container .section__header {
        max-width: 700px;
        margin-inline: auto;
        margin-bottom: 4rem;
        text-align: center;
      }

      .range__grid {
        display: grid;
        gap: 1.5rem;
      }

      .range__card {
        position: relative;
        isolation: isolate;
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.2);
      }

      .range__card img {
        transition: 0.3s;
      }

      .range__card:hover img {
        transform: scale(1.1);
      }

      .range__details {
        position: absolute;
        inset: 0;
        padding: 2rem;
        background-image: linear-gradient(
          to bottom right,
          rgba(0, 0, 0, 0.8),
          transparent 50%,
          rgba(0, 0, 0, 0.8)
        );
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }

      .range__details h4 {
        font-size: 3rem;
        font-weight: 600;
        font-family: var(--header-font);
        color: var(--white);
      }

      .range__details a {
        align-self: flex-start;
        padding: 5px 10px;
        font-size: 1.5rem;
        color: var(--white);
        border: 2px solid var(--white);
        border-radius: 100%;
      }

      /* --- FOOTER --- */
      footer {
        background-color: var(--text-dark);
        padding-top: 5rem;
      }

      .footer__container {
        display: grid;
        gap: 4rem 2rem;
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
      @media (width > 768px) {
  /* This ensures the car cards wrap neatly in a 3-column layout on desktop */
      .range__grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
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
          line-height: 3rem;
        }
        .header__container form {
          grid-template-columns: 1fr;
        }
        .checkbox_group,
        .dropoff_group,
        .header__container form .btn {
          grid-column: 1;
        }
      }

    .date-time-wrapper {
    display: flex;
    gap: 5px;
    }

    .date-time-wrapper input[type="date"] {
    flex: 2; /* Date gets more space */
    }

    .date-time-wrapper input[type="time"] {
    flex: 1; /* Time gets less space */
    }
    </style>
    <title>Hasta Car Rental</title>
  </head>
  <body>
    <header>
      <nav id="navbar">
        <div class="nav__header">
          <div class="nav__logo">
            <a href="index.html">
              <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta" />
            </a>
          </div>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-line"></i>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="#home">Home</a></li>
          <li><a href="#vehicles">Vehicles</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="#about">About Us</a></li>
        </ul>
        <div class="nav__btn">
          <button
            class="btn"
            style="background: transparent; color: white; border: 1px solid white;"
          >
            Login
          </button>
          <button class="btn" style="background-color: var(--primary-color);">
            Register
          </button>
        </div>
      </nav>

      <div class="header__container" id="home">
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
            <div class = "date-time-wrapper">
              <input type="date" name="start_date" id="start" required>
              <input type="time" name="start_time" required>
            </div>
          </div>

          <div class="input__group">
            <label for="stop">End Date & Time</label>
            <div class = "date-time-wrapper">
              <input type="date" name="stop_date" id="stop" required>
              <input type="time" name="stop_time" required>
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
        <img src="assets/header.png" alt="car overlay" />
      </div>
      <a href="#about" class="scroll__down">
        <i class="ri-arrow-down-line"></i>
      </a>
    </header>

   
  <section class="section__container range__container" id="vehicle">
  <h2 class="section__header">OUR RANGE OF VEHICLES</h2>
  <div class="range__grid">
    
    <div class="range__card">
      <img src="{{ asset('images/PERODUA-Axia-5639_10.jpeg') }}" alt="Perodua Axia" />
      <div class="range__details">
        <h4>Perodua<br />Axia</h4>
        <a href="#"><i class="ri-arrow-right-line"></i></a>
      </div>
    </div>

    <div class="range__card">
      <img src="{{ asset('images/PERODUA-Axia-5639_10.jpeg') }}" alt="Perodua Myvi" />
      <div class="range__details">
        <h4>Perodua<br />Myvi</h4>
        <a href="#"><i class="ri-arrow-right-line"></i></a>
      </div>
    </div>

    <div class="range__card">
      <img src="{{ asset('images/PERODUA-Axia-5639_10.jpeg') }}" alt="Perodua Bezza" />
      <div class="range__details">
        <h4>Perodua<br />Bezza</h4>
        <a href="#"><i class="ri-arrow-right-line"></i></a>
      </div>
    </div>

    <div class="range__card">
      <img src="{{ asset('images/PERODUA-Axia-5639_10.jpeg') }}" alt="Honda Dash 125" />
      <div class="range__details">
        <h4>Honda<br />Dash 125</h4>
        <a href="#"><i class="ri-arrow-right-line"></i></a>
      </div>
    </div>

    <div class="range__card">
      <img src="{{ asset('images/PERODUA-Axia-5639_10.jpeg') }}" alt="Honda Beat 110" />
      <div class="range__details">
        <h4>Honda<br />Beat 110</h4>
        <a href="#"><i class="ri-arrow-right-line"></i></a>
      </div>
    </div>

  </div>
</section>

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
        <p style="color: var(--text-light); text-align: center; padding: 2rem;">
          © 2025 Hasta Car Rental. All rights reserved.
        </p>
      </div>
    </footer>

    <script>
      // Navigation Toggle
      const menuBtn = document.getElementById("menu-btn");
      const navLinks = document.getElementById("nav-links");

      menuBtn.addEventListener("click", () => {
        navLinks.classList.toggle("open");
      });

      // Navbar Scroll Transition
      const navbar = document.getElementById("navbar");
      window.addEventListener("scroll", () => {
        if (window.scrollY > 50) {
          navbar.classList.add("nav__fixed");
        } else {
          navbar.classList.remove("nav__fixed");
        }
      });

      // Drop-off Location Visibility Logic
      const sameLocationCheckbox = document.getElementById("same-location");
      const dropoffInputGroup = document.getElementById("dropoff-input-group");

      sameLocationCheckbox.addEventListener("change", () => {
        if (sameLocationCheckbox.checked) {
          dropoffInputGroup.style.display = "none";
        } else {
          dropoffInputGroup.style.display = "flex";
        }
      });

      // Initial state check
      if (sameLocationCheckbox.checked) {
        dropoffInputGroup.style.display = "none";
      } else {
        dropoffInputGroup.style.display = "flex";
      }
    </script>
  </body>
</html>