<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Inspection - Hasta Car Rental</title>
    
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

        /* Footer Style */
        footer { background-color: #2d3748; padding-top: 3rem; color: #fff; margin-top: auto; }
        .footer__container { 
            max-width: 1200px; margin: auto; padding: 0 1rem 2rem; 
            display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 2rem;
            border-bottom: 1px solid #333;
        }
        .footer__col h4 { font-family: var(--header-font); margin-bottom: 1.5rem; }
        .footer__links li { margin-bottom: 0.8rem; list-style: none; }
        .footer__links a { color: var(--text-light); text-decoration: none; }
        .footer__links a:hover { color: var(--primary-color); }

        /* Animation */
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        /* Custom Input Range */
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #ec5a29;
            cursor: pointer;
            margin-top: -8px;
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #e2e8f0;
            border-radius: 2px;
        }
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
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#ec5a29]">
                        <i class="ri-user-line mr-2 align-middle"></i> My Profile
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
    </nav>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <div class="mb-6">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition">
                    <i class="ri-arrow-left-line mr-2"></i> 
                    Back to Profile
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ (isset($type) && $type === 'return') ? 'Return Vehicle' : 'Vehicle Inspection' }}
                        </h1>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ (isset($type) && $type === 'return') ? 'Return Verification' : 'Pickup Verification' }}
                        </p>
                    </div>
                    <div class="text-right">
                         <span class="px-3 py-1 bg-[#ec5a29]/10 text-[#ec5a29] rounded-full text-xs font-bold uppercase tracking-wider">
                            Booking #{{ $booking->id }}
                         </span>
                    </div>
                </div>

                <form action="{{ route('inspections.store', $booking) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf
                    @csrf
                    <input type="hidden" name="type" value="{{ $type ?? 'pickup' }}">

                    <!-- Instructions -->
                    @if(isset($type) && $type === 'return')
                        <!-- Return Instructions -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="ri-information-line text-blue-400 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-blue-800 uppercase tracking-wide">
                                        Return Procedure
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p class="mb-2 font-bold text-black">
                                            Returning : {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->plate_number }})
                                        </p>
                                        <ol class="list-decimal list-inside space-y-1">
                                            <li><strong>Oil:</strong> Make sure oil level is returned the same as it was given. If overfill, no refund will be given.</li>
                                            <li><strong>Deposit:</strong> Returned if car is in good condition, oil same as given, no summons, clean, and on time.</li>
                                            <li><strong>Return Key:</strong> Put the key in <strong>front passenger seat's compartment (Glove Box)</strong>.</li>
                                            <li><strong>Photo:</strong> Take a picture of oil bar, <strong>car parked at drop off location</strong>, and <strong>the key placed in the compartment</strong>.</li>
                                        </ol>
                                        <p class="mt-2 text-xs font-bold text-red-600">
                                            * Penalty will be imposed if returned late or oil is less than given.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Pickup Instructions -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="ri-alert-line text-yellow-400 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-yellow-800 uppercase tracking-wide">
                                        Pickup Procedure
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p class="mb-2 font-semibold">Please follow this procedure correctly or you will be penalized.</p>
                                        <p class="mb-2 font-bold text-black">
                                            Car : {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->plate_number }})
                                        </p>
                                        <ol class="list-decimal list-inside space-y-1">
                                            <li>Read and agree to the agreement.</li>
                                            <li>Take the car key below driver's seat.</li>
                                            <li>Snap 4 sides pictures of the car & mileage and fuel of the car.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Vehicle Info Card -->
                    <div class="flex items-center p-4 bg-blue-50 border border-blue-100 rounded-xl gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                            <i class="ri-car-fill text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</h3>
                            <p class="text-xs text-gray-500 font-mono">{{ $booking->vehicle->plate_number }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Mileage -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Current Mileage (km)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="ri-speed-mini-fill"></i>
                                </span>
                                <input type="number" name="mileage" class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#ec5a29] focus:border-transparent outline-none transition" placeholder="e.g. 45000" required>
                            </div>
                        </div>

                        <!-- Fuel Level -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Fuel Level</label>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <div class="flex justify-between mb-2">
                                    <span class="text-xs font-bold text-red-500">Empty</span>
                                    <span class="text-lg font-bold text-[#ec5a29]" id="fuelDisplay">10</span>
                                    <span class="text-xs font-bold text-green-500">Full</span>
                                </div>
                                <input type="range" name="fuel_level" min="0" max="10" step="1" value="10" 
                                    class="w-full appearance-none bg-transparent"
                                    oninput="document.getElementById('fuelDisplay').innerText = this.value">
                                <div class="flex justify-between text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-wider">
                                    <span>0</span><span>5</span><span>10</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Upload Section -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-camera-lens-line text-[#ec5a29]"></i> Vehicle Photos
                        </h3>
                        
                        @if(isset($type) && $type === 'return')
                            <!-- Return Photos (2 Required) -->
                            <p class="text-sm text-gray-500 mb-6">Please upload the required return photos.</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Car Parked Front of Store (Mapped to photo_front) -->
                                <div x-data="{ preview: null }" class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-700 text-center uppercase tracking-wide">
                                        Car Parked at drop off location
                                    </label>
                                    <label class="block cursor-pointer relative group h-48">
                                        <input type="file" name="photo_front" accept="image/*" class="hidden" required 
                                               @change="preview = URL.createObjectURL($event.target.files[0])">
                                        <div class="w-full h-full rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50 group-hover:border-[#ec5a29] group-hover:bg-[#ec5a29]/5 transition overflow-hidden">
                                            <template x-if="!preview">
                                                <div class="text-center p-2">
                                                    <i class="ri-map-pin-2-line text-3xl text-gray-400 group-hover:text-[#ec5a29]"></i>
                                                    <span class="block text-[10px] text-gray-400 mt-2">Upload Drop-off Point</span>
                                                </div>
                                            </template>
                                            <template x-if="preview">
                                                <img :src="preview" class="w-full h-full object-cover">
                                            </template>
                                        </div>
                                    </label>
                                </div>

                                <!-- Oil Bar (Mapped to photo_dashboard) -->
                                <div x-data="{ preview: null }" class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-700 text-center uppercase tracking-wide">
                                        Oil Bar / Fuel Gauge
                                    </label>
                                    <label class="block cursor-pointer relative group h-48">
                                        <input type="file" name="photo_dashboard" accept="image/*" class="hidden" required 
                                               @change="preview = URL.createObjectURL($event.target.files[0])">
                                        <div class="w-full h-full rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50 group-hover:border-[#ec5a29] group-hover:bg-[#ec5a29]/5 transition overflow-hidden">
                                            <template x-if="!preview">
                                                <div class="text-center p-2">
                                                    <i class="ri-gas-station-line text-3xl text-gray-400 group-hover:text-[#ec5a29]"></i>
                                                    <span class="block text-[10px] text-gray-400 mt-2">Upload Fuel Gauge</span>
                                                </div>
                                            </template>
                                            <template x-if="preview">
                                                <img :src="preview" class="w-full h-full object-cover">
                                            </template>
                                        </div>
                                    </label>
                                </div>

                                <!-- Key Photo (Mapped to photo_keys) -->
                                <div x-data="{ preview: null }" class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-700 text-center uppercase tracking-wide">
                                        Key in Compartment (Glove Box)
                                    </label>
                                    <label class="block cursor-pointer relative group h-48">
                                        <input type="file" name="photo_keys" accept="image/*" class="hidden" required 
                                               @change="preview = URL.createObjectURL($event.target.files[0])">
                                        <div class="w-full h-full rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50 group-hover:border-[#ec5a29] group-hover:bg-[#ec5a29]/5 transition overflow-hidden">
                                            <template x-if="!preview">
                                                <div class="text-center p-2">
                                                    <i class="ri-key-2-line text-3xl text-gray-400 group-hover:text-[#ec5a29]"></i>
                                                    <span class="block text-[10px] text-gray-400 mt-2">Upload Key Photo</span>
                                                </div>
                                            </template>
                                            <template x-if="preview">
                                                <img :src="preview" class="w-full h-full object-cover">
                                            </template>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @else
                            <!-- Pickup Photos (Standard 5) -->
                            <p class="text-sm text-gray-500 mb-6">Please upload clear photos of the vehicle from all 4 sides and the dashboard.</p>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <!-- Front -->
                                <div x-data="{ preview: null }" class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-700 text-center uppercase tracking-wide">Front View</label>
                                    <label class="block cursor-pointer relative group">
                                        <input type="file" name="photo_front" accept="image/*" class="hidden" required 
                                               @change="preview = URL.createObjectURL($event.target.files[0])">
                                        <div class="aspect-square rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50 group-hover:border-[#ec5a29] group-hover:bg-[#ec5a29]/5 transition overflow-hidden">
                                            <template x-if="!preview">
                                                <div class="text-center p-2">
                                                    <i class="ri-image-add-line text-2xl text-gray-400 group-hover:text-[#ec5a29]"></i>
                                                    <span class="block text-[10px] text-gray-400 mt-1">Click to Upload</span>
                                                </div>
                                            </template>
                                            <template x-if="preview">
                                                <img :src="preview" class="w-full h-full object-cover">
                                            </template>
                                        </div>
                                    </label>
                                </div>
    
                                <!-- Back -->
                                <div x-data="{ preview: null }" class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-700 text-center uppercase tracking-wide">Back View</label>
                                    <label class="block cursor-pointer relative group">
                                        <input type="file" name="photo_back" accept="image/*" class="hidden" required 
                                               @change="preview = URL.createObjectURL($event.target.files[0])">
                                        <div class="aspect-square rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50 group-hover:border-[#ec5a29] group-hover:bg-[#ec5a29]/5 transition overflow-hidden">
                                            <template x-if="!preview">
                                                <div class="text-center p-2">
                                                    <i class="ri-image-add-line text-2xl text-gray-400 group-hover:text-[#ec5a29]"></i>
                                                    <span class="block text-[10px] text-gray-400 mt-1">Click to Upload</span>
                                                </div>
                                            </template>
                                            <template x-if="preview">
                                                <img :src="preview" class="w-full h-full object-cover">
                                            </template>
                                        </div>
                                    </label>
                                </div>
    
                                <!-- Left -->
                                <div x-data="{ preview: null }" class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-700 text-center uppercase tracking-wide">Left Side</label>
                                    <label class="block cursor-pointer relative group">
                                        <input type="file" name="photo_left" accept="image/*" class="hidden" required 
                                               @change="preview = URL.createObjectURL($event.target.files[0])">
                                        <div class="aspect-square rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50 group-hover:border-[#ec5a29] group-hover:bg-[#ec5a29]/5 transition overflow-hidden">
                                            <template x-if="!preview">
                                                <div class="text-center p-2">
                                                    <i class="ri-image-add-line text-2xl text-gray-400 group-hover:text-[#ec5a29]"></i>
                                                    <span class="block text-[10px] text-gray-400 mt-1">Click to Upload</span>
                                                </div>
                                            </template>
                                            <template x-if="preview">
                                                <img :src="preview" class="w-full h-full object-cover">
                                            </template>
                                        </div>
                                    </label>
                                </div>
    
                                <!-- Right -->
                                <div x-data="{ preview: null }" class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-700 text-center uppercase tracking-wide">Right Side</label>
                                    <label class="block cursor-pointer relative group">
                                        <input type="file" name="photo_right" accept="image/*" class="hidden" required 
                                               @change="preview = URL.createObjectURL($event.target.files[0])">
                                        <div class="aspect-square rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50 group-hover:border-[#ec5a29] group-hover:bg-[#ec5a29]/5 transition overflow-hidden">
                                            <template x-if="!preview">
                                                <div class="text-center p-2">
                                                    <i class="ri-image-add-line text-2xl text-gray-400 group-hover:text-[#ec5a29]"></i>
                                                    <span class="block text-[10px] text-gray-400 mt-1">Click to Upload</span>
                                                </div>
                                            </template>
                                            <template x-if="preview">
                                                <img :src="preview" class="w-full h-full object-cover">
                                            </template>
                                        </div>
                                    </label>
                                </div>
                                
                            </div>
                            
                            <!-- Dashboard -->
                            <div x-data="{ preview: null }" class="mt-4 max-w-xs">
                                 <label class="block text-xs font-bold text-gray-700 text-center uppercase tracking-wide mb-2">Dashboard / Interior</label>
                                 <label class="block cursor-pointer relative group">
                                    <input type="file" name="photo_dashboard" accept="image/*" class="hidden" required 
                                           @change="preview = URL.createObjectURL($event.target.files[0])">
                                    <div class="h-24 rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50 group-hover:border-[#ec5a29] group-hover:bg-[#ec5a29]/5 transition overflow-hidden">
                                        <template x-if="!preview">
                                            <div class="text-center flex items-center gap-2">
                                                <i class="ri-image-add-line text-lg text-gray-400 group-hover:text-[#ec5a29]"></i>
                                                <span class="text-[10px] text-gray-400">Upload Interior</span>
                                            </div>
                                        </template>
                                        <template x-if="preview">
                                            <img :src="preview" class="w-full h-full object-cover">
                                        </template>
                                    </div>
                                 </label>
                            </div>
                        @endif
                    </div>

                    <!-- Damage Reporting (Optional) -->
                    <div class="bg-red-50 p-6 rounded-xl border border-red-100">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-alert-line text-red-500"></i> Report Damage (Optional)
                        </h3>
                        <p class="text-xs text-gray-500 mb-4">If there is any visible damage, please upload a photo and describe it below.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Damage Photo -->
                            <!-- Damage Photos -->
                            <!-- Damage Photos -->
                            <div x-data="damagePhotos()">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Damage Photos</label>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Loop through file inputs -->
                                    <template x-for="(file, index) in files" :key="file.id">
                                        <div class="relative group">
                                            
                                            <!-- Setup: Hidden Input (always present to submit data) -->
                                            <!-- We wrap it in a label if it's the 'add' button, otherwise it's just hidden -->
                                            
                                            <!-- CASE 1: Has File (Preview Mode) -->
                                            <div x-show="file.preview" class="h-full">
                                                <div class="aspect-square rounded-xl overflow-hidden border border-gray-200 relative">
                                                    <img :src="file.preview" class="w-full h-full object-cover">
                                                    
                                                    <!-- Delete Button -->
                                                    <button type="button" @click="removeFile(index)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md hover:bg-red-600 transition">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- CASE 2: No File (Add Button Mode) -->
                                            <div x-show="!file.preview" class="h-full">
                                                <label class="cursor-pointer block h-full">
                                                    <div class="aspect-square rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-white group-hover:border-[#ec5a29] group-hover:bg-[#ec5a29]/5 transition overflow-hidden">
                                                        <div class="text-center p-2">
                                                            <i class="ri-add-circle-line text-3xl text-gray-400 group-hover:text-[#ec5a29]"></i>
                                                            <span class="block text-[10px] text-gray-400 mt-1 font-bold">ADD PHOTO</span>
                                                        </div>
                                                    </div>
                                                    <!-- The Actual Input -->
                                                    <input type="file" name="damage_photos[]" accept="image/*" class="hidden" 
                                                           @change="handleFileSelect($event, index)">
                                                </label>
                                            </div>

                                        </div>
                                    </template>
                                </div>

                                <script>
                                    function damagePhotos() {
                                        return {
                                            files: [
                                                { id: Date.now(), preview: null } // Start with one empty slot
                                            ],
                                            handleFileSelect(event, index) {
                                                const file = event.target.files[0];
                                                if (file) {
                                                    // Set preview for current
                                                    this.files[index].preview = URL.createObjectURL(file);
                                                    
                                                    // Add new empty slot for next photo
                                                    this.files.push({ id: Date.now() + 1, preview: null });
                                                }
                                            },
                                            removeFile(index) {
                                                // Remove the item at index
                                                this.files.splice(index, 1);
                                                // If we somehow removed the last empty one (shouldn't happen as we only show delete on preview ones)
                                                // ensure there is always at least one empty slot? 
                                                // Actually our logic: only preview ones have delete button. The last empty one doesn't.
                                                // So we are safe.
                                            }
                                        }
                                    }
                                </script>
                            </div>

                            <!-- Damage Description -->
                            <div class="md:col-span-2 flex flex-col">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Description of Damage</label>
                                <textarea name="damage_description" class="w-full flex-grow p-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition text-sm resize-none" placeholder="Describe the damage (e.g. scratch on left bumper)..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Detailed Remarks</label>
                        <textarea name="remarks" rows="4" class="w-full p-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#ec5a29] focus:border-transparent outline-none transition text-sm" placeholder="Please describe any pre-existing scratches, dents, or cleanliness issues..."></textarea>
                    </div>

                    <!-- Customer Feedback (Return Only) -->
                    @if(isset($type) && $type === 'return')
                    <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100 mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                <i class="ri-thumb-up-line text-indigo-600"></i> Vehicle Feedback
                            </h3>
                            <span class="text-[10px] bg-indigo-100 text-indigo-700 px-2 py-1 rounded font-bold uppercase tracking-wider">Stakeholder Request</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-6 italic">Please tick the boxes if you found any issues during your rental period.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 mb-8">
                            {{-- 1. Interior --}}
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-indigo-50 shadow-sm hover:border-indigo-200 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <i class="ri-brush-2-line"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Interior is Dirty / Trash found</span>
                                </div>
                                <input type="checkbox" name="issue_interior" value="1" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            </div>

                            {{-- 2. Smell --}}
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-indigo-50 shadow-sm hover:border-indigo-200 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <i class="ri-windy-line"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Bad Smell (Cigarette/Food)</span>
                                </div>
                                <input type="checkbox" name="issue_smell" value="1" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            </div>

                            {{-- 3. Mechanical --}}
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-indigo-50 shadow-sm hover:border-indigo-200 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <i class="ri-settings-4-line"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Mechanical Issue / Noise</span>
                                </div>
                                <input type="checkbox" name="issue_mechanical" value="1" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            </div>

                            {{-- 4. Aircond --}}
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-indigo-50 shadow-sm hover:border-indigo-200 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <i class="ri-temp-cold-line"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Aircond Not Cold</span>
                                </div>
                                <input type="checkbox" name="issue_ac" value="1" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            </div>

                            {{-- 5. Exterior --}}
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-indigo-50 shadow-sm hover:border-indigo-200 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <i class="ri-car-washing-line"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Exterior is very dirty</span>
                                </div>
                                <input type="checkbox" name="issue_exterior" value="1" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            </div>

                            {{-- 6. Safety --}}
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-indigo-50 shadow-sm hover:border-indigo-200 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <i class="ri-shield-cross-line"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Safety Concern (Brakes/Lights)</span>
                                </div>
                                <input type="checkbox" name="issue_safety" value="1" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- NOTICE ABOUT STATUS -->
                        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-information-line text-amber-400 text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-amber-800">
                                        <strong>Note:</strong> Ticking any of the boxes above will naturally mark the vehicle for <strong>Maintenance</strong> to ensure quality for the next user.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Optional Feedback -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Additional Comments / Details of Issues</label>
                            <textarea name="feedback_text" rows="3" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Please provide more details if you checked any issues above..."></textarea>
                        </div>
                    </div>
                    @endif

                    <!-- Rental Agreement (Pickup Only) -->
                    @if(!isset($type) || $type !== 'return')
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-file-list-3-line text-[#ec5a29]"></i> Rental Agreement
                        </h3>
                        <div class="mb-4 text-center">
                            <img src="{{ asset('images/agreementform.png') }}" alt="Rental Agreement" class="max-w-full h-auto rounded-lg border border-gray-300 shadow-sm mx-auto">
                            <a href="{{ asset('images/agreementform.png') }}" target="_blank" class="text-xs text-[#ec5a29] font-bold mt-2 inline-block hover:underline">
                                <i class="ri-zoom-in-line"></i> View Full Size
                            </a>
                        </div>
                        
                        <div class="flex items-start gap-3 mt-4 p-4 bg-white rounded-lg border border-gray-100">
                             <div class="flex items-center h-5">
                                <input id="agreement_check" name="agreement_check" type="checkbox" required class="w-4 h-4 text-[#ec5a29] border-gray-300 rounded focus:ring-[#ec5a29]">
                            </div>
                            <div class="text-sm">
                                <label for="agreement_check" class="font-medium text-gray-900">I have read and agree to the Rental Agreement Terms & Conditions</label>
                                <p class="text-gray-500 text-xs">You must agree to the terms above to proceed with the rental.</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Acknowledgement -->
                    <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex items-start gap-3">
                        <div class="flex items-center h-5">
                            <input id="acknowledgement" aria-describedby="acknowledgement-description" name="acknowledgement" type="checkbox" required class="w-4 h-4 text-[#ec5a29] border-gray-300 rounded focus:ring-[#ec5a29]">
                        </div>
                        <div class="text-sm">
                            <label for="acknowledgement" class="font-medium text-gray-900">I hereby acknowledge that all information above are true and accurate</label>
                            <p id="acknowledgement-description" class="text-gray-500 text-xs">By checking this box, you confirm that the vehicle condition logs and photos uploaded are genuine.</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-[#ec5a29] hover:bg-[#d14a1e] text-white font-bold rounded-xl shadow-lg shadow-orange-500/30 transition transform active:scale-95 flex items-center">
                            Submit Inspection <i class="ri-check-line ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <p class="text-center text-xs text-gray-400 mt-8">
                By submitting, you confirm the vehicle condition is as stated above.
            </p>
        </div>
    </main>

    <footer id="about">
        <div class="footer__container">
            <div class="footer__col">
                <h4>Hasta Car Rental</h4>
                <p class="text-[#737373] leading-relaxed mb-4">
                    Experience the freedom of the road with our premium car rental services. 
                    Reliable, affordable, and convenient vehicles for every journey.
                </p>
            </div>

            <div class="footer__col">
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
                <div class="flex gap-4 mb-6">
                    <a href="https://www.facebook.com/hastatraveltours" class="text-gray-400 hover:text-[#ec5a29] text-xl border-2 border-gray-400 rounded-full w-10 h-10 flex items-center justify-center transition hover:border-[#ec5a29]"><i class="ri-facebook-fill"></i></a>
                    <a href="https://www.instagram.com/hastatraveltours/?hl=en" class="text-gray-400 hover:text-[#ec5a29] text-xl border-2 border-gray-400 rounded-full w-10 h-10 flex items-center justify-center transition hover:border-[#ec5a29]"><i class="ri-instagram-fill"></i></a>
                    <a href="https://x.com/hastacarrental" class="text-gray-400 hover:text-[#ec5a29] text-xl border-2 border-gray-400 rounded-full w-10 h-10 flex items-center justify-center transition hover:border-[#ec5a29]"><i class="ri-twitter-fill"></i></a>
                    <a href="https://www.linkedin.com/company/hasta-travel/" class="text-gray-400 hover:text-[#ec5a29] text-xl border-2 border-gray-400 rounded-full w-10 h-10 flex items-center justify-center transition hover:border-[#ec5a29]"><i class="ri-linkedin-fill"></i></a>
                </div>

                <div>
                    <p class="text-[#737373] text-sm mb-2 flex items-center gap-2">
                        <i class="ri-phone-line text-[#ec5a29]"></i> 
                        <span>Car Rental Inquiry:<br><strong class="text-[#cbd5e1]">+60 11-1090 0700</strong></span>
                    </p>
                    <p class="text-[#737373] text-sm mb-2 flex items-center gap-2">
                        <i class="ri-mail-line text-[#ec5a29]"></i> 
                        <span>Support:<br><strong class="text-[#cbd5e1]">hastatraveltours@gmail.com</strong></span>
                    </p>
                </div>
            </div>
        </div>
        <div class="text-center text-[#737373] py-8 text-sm bg-[#2d3748]">
            © 2026 Hasta Car Rental. All rights reserved.
        </div>
    </footer>

</body>
</html>
