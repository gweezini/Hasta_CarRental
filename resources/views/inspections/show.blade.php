<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Details - Hasta Car Rental</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap");
        @import url("https://fonts.googleapis.com/css2?family=Syncopate:wght@400;700&display=swap");

        :root {
            --primary-color: #ec5a29;
            --text-dark: #15191d;
            --text-light: #737373;
            --white: #ffffff;
            --header-font: "Syncopate", sans-serif;
        }

        body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }

        /* --- NAVIGATION STYLES --- */
        nav {
            background-color: #2d3748;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .nav__btn { display: flex; align-items: center; gap: 1.5rem; }

        footer { background-color: var(--text-dark); padding-top: 3rem; color: #fff; margin-top: auto; }
        .footer__container { 
            max-width: 1200px; margin: auto; padding: 0 1rem 2rem; 
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem;
            border-bottom: 1px solid #333;
        }
        .footer__col h4 { font-family: var(--header-font); margin-bottom: 1.5rem; }
        .footer__links li { margin-bottom: 0.8rem; list-style: none; }
        .footer__links a { color: var(--text-light); text-decoration: none; }
        .footer__links a:hover { color: var(--primary-color); }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <nav>
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="hover:opacity-80 transition">
                <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta Logo" class="h-10 rounded shadow border-2 border-white/20">
            </a>
        </div>
        <div class="nav__btn">
            <div x-data="{ userOpen: false }" class="relative">
                <button @click="userOpen = !userOpen" @click.away="userOpen = false" class="flex items-center gap-2 group focus:outline-none">
                     <img class="h-9 w-9 rounded-full object-cover border-2 border-transparent group-hover:border-[#ec5a29] transition" 
                          src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ec5a29&color=fff" 
                          alt="Profile">
                </button>
            </div>
        </div>
    </nav>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <div class="mb-6 flex justify-between items-center">
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'topmanagement')
                     <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition">
                        <i class="ri-arrow-left-line mr-2"></i> 
                        Back to Dashboard
                    </a>
                @else
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition">
                        <i class="ri-arrow-left-line mr-2"></i> 
                        Back to Profile
                    </a>
                @endif
                <span class="px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider bg-green-100 text-green-700">
                    Inspection Verified
                </span>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-8 py-6 border-b border-gray-100">
                     <h1 class="text-2xl font-bold text-gray-900">Inspection Report</h1>
                     <p class="text-sm text-gray-500 mt-1">Recorded on {{ $inspection->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div class="p-8 space-y-8">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-xs text-gray-400 font-bold uppercase">Vehicle</p>
                            <p class="text-lg font-bold text-gray-800">{{ $inspection->booking->vehicle->brand }} {{ $inspection->booking->vehicle->model }}</p>
                            <p class="text-xs text-gray-500">{{ $inspection->booking->vehicle->plate_number }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-xs text-gray-400 font-bold uppercase">Mileage</p>
                            <p class="text-lg font-bold text-gray-800">{{ number_format($inspection->mileage) }} km</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-xs text-gray-400 font-bold uppercase">Fuel Level</p>
                            <div class="flex items-center gap-2 mt-1">
                                 <div class="flex-1 bg-gray-200 rounded-full h-2">
                                     <div class="bg-[#ec5a29] h-2 rounded-full" style="width: {{ ($inspection->fuel_level/10)*100 }}%"></div>
                                 </div>
                                 <span class="font-bold text-gray-800">{{ $inspection->fuel_level }}/10</span>
                            </div>
                        </div>
                    </div>

                    <!-- Photos -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-camera-line text-[#ec5a29]"></i> Captured Photos
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach(['prefix' => 'photo_'] as $prefix)
                                @foreach(['front', 'back', 'left', 'right', 'dashboard'] as $view)
                                    @php $col = $prefix . $view; @endphp
                                    @if($inspection->$col)
                                        <div class="space-y-2 group">
                                            <p class="text-xs font-bold text-gray-500 uppercase text-center">{{ ucfirst($view) }}</p>
                                            <div class="relative overflow-hidden rounded-xl border border-gray-200 aspect-square shadow-sm group-hover:shadow-md transition">
                                                <a href="{{ asset('storage/'.$inspection->$col) }}" target="_blank">
                                                    <img src="{{ asset('storage/'.$inspection->$col) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    <!-- Remarks -->
                    @if($inspection->remarks)
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">Remarks</h3>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 text-gray-700 italic">
                            "{{ $inspection->remarks }}"
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer__container">
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
                </ul>
            </div>
            <div class="footer__col">
                <h4>Follow Us</h4>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-[#ec5a29] text-xl"><i class="ri-facebook-fill"></i></a>
                    <a href="#" class="text-gray-400 hover:text-[#ec5a29] text-xl"><i class="ri-instagram-fill"></i></a>
                    <a href="#" class="text-gray-400 hover:text-[#ec5a29] text-xl"><i class="ri-twitter-fill"></i></a>
                </div>
            </div>
        </div>
        <div class="text-center text-gray-500 pb-8 text-sm">
            © 2025 Hasta Car Rental. All rights reserved.
        </div>
    </footer>
</body>
</html>
