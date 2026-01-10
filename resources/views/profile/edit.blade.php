<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Hasta Car Rental</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    
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

        /* Responsive */
        @media (max-width: 768px) {
            .footer__container { grid-template-columns: 1fr 1fr; }
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

        <!-- Added Navigation Links -->
        <ul class="nav__links hidden md:flex items-center gap-8 text-white font-medium"> 
            <li><a href="{{ route('dashboard') }}" class="hover:text-[#ec5a29] transition">Dashboard</a></li>
            <li><a href="{{ route('dashboard') }}#vehicles" class="hover:text-[#ec5a29] transition">Vehicles</a></li>
            <li><a href="#contact" class="hover:text-[#ec5a29] transition">Contact</a></li>
            <li><a href="#about" class="hover:text-[#ec5a29] transition">About Us</a></li>
        </ul>

        <div class="nav__btn">
            
            <div x-data="{ open: false }" class="relative flex items-center">
                <button @click="open = !open" @click.away="open = false" class="relative text-white hover:text-[#ec5a29] transition p-1">
                    <i class="ri-notification-3-line text-2xl"></i>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-0 right-0 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600 border-2 border-[#15191d]"></span>
                        </span>
                    @endif
                </button>

                <div x-show="open" style="display: none;" 
                     x-transition.origin.top.right
                     class="absolute right-0 top-full mt-3 w-80 bg-white rounded-xl shadow-2xl overflow-hidden z-50 border border-gray-100 text-gray-800">
                    
                    <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center bg-gray-50">
                        <span class="text-xs font-bold uppercase text-gray-500 tracking-wider">Notifications</span>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">{{ Auth::user()->unreadNotifications->count() }} New</span>
                        @endif
                    </div>

                    <div class="max-h-64 overflow-y-auto">
                        @forelse(Auth::user()->notifications->take(3) as $notification)
                            <a href="{{ route('profile.edit', ['tab' => 'booking']) }}#booking-{{ $notification->data['booking_id'] ?? '' }}" 
                               class="block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition flex items-start gap-3"
                               onclick="jumpToBooking(event, 'booking-{{ $notification->data['booking_id'] ?? '' }}')">
                                <div class="mt-1">
                                    @if(isset($notification->data['status']) && $notification->data['status'] == 'Approved')
                                        <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><i class="ri-check-line text-xs"></i></div>
                                    @elseif(isset($notification->data['status']) && $notification->data['status'] == 'Rejected')
                                        <div class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center"><i class="ri-close-line text-xs"></i></div>
                                    @elseif(isset($notification->data['type']) && $notification->data['type'] == 'success')
                                        <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><i class="ri-refund-2-line text-xs"></i></div>
                                    @else
                                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="ri-notification-line text-xs"></i></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 leading-tight">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        @empty
                            <div class="px-4 py-6 text-center text-xs text-gray-400">No notifications</div>
                        @endforelse
                    </div>
                    <button onclick="openTab('notifications');" class="w-full text-center text-xs font-bold text-[#ec5a29] py-3 hover:bg-gray-50 transition border-t border-gray-100 block">
                        View All
                    </button>
                </div>
            </div>

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
    </nav>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-[#15191d] text-white text-xs font-bold uppercase tracking-widest rounded-lg hover:bg-[#ec5a29] transition shadow-md hover:shadow-lg transform active:scale-95">
                    <i class="ri-arrow-left-line mr-2 text-base"></i> 
                    Back to Dashboard
                </a>
            </div>
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your bookings and profile information</p>
            </div>

            <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-100 inline-flex items-center mb-6 overflow-x-auto max-w-full">
                <button onclick="openTab('booking')" id="tab-booking" class="px-6 py-2.5 text-sm font-bold rounded-lg shadow-sm bg-[#ec5a29] text-white transition-all whitespace-nowrap mr-2">
                    Booking History
                </button>

                <button onclick="openTab('rewards')" id="tab-rewards" class="px-6 py-2.5 text-sm font-medium rounded-lg text-gray-500 hover:bg-gray-50 transition-all whitespace-nowrap mr-2">
                    My Rewards
                </button>

                <button onclick="openTab('personal')" id="tab-personal" class="px-6 py-2.5 text-sm font-medium rounded-lg text-gray-500 hover:bg-gray-50 transition-all whitespace-nowrap mr-2">
                    My Profile
                </button>

                <button onclick="openTab('notifications')" id="tab-notifications" class="relative px-6 py-2.5 text-sm font-medium rounded-lg text-gray-500 hover:bg-gray-50 transition-all whitespace-nowrap flex items-center gap-2">
                    Notifications
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ Auth::user()->unreadNotifications->count() }}</span>
                    @endif
                </button>
            </div>

            @php
                $allBookings = $bookings ?? collect();
                // 筛选逻辑
                $pastBookings = $allBookings->filter(function($b) {
                    return in_array($b->status, ['Rejected', 'Returned', 'Completed', 'Cancelled']) || ($b->return_date_time && \Carbon\Carbon::parse($b->return_date_time)->lt(now()));
                });
                $ongoingBookings = $allBookings->diff($pastBookings);
            @endphp
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                    </span>
                </div>
            @endif

            <div id="content-booking" class="animate-fade-in">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 md:p-8">
                    
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="ri-car-line text-[#ec5a29]"></i> Ongoing Bookings
                    </h2>
                    
                    @if($ongoingBookings->count() > 0)
                        <div class="space-y-4 mb-10">
                            @foreach($ongoingBookings as $booking)
                                <div id="booking-{{ $booking->id }}" class="border border-blue-100 bg-white rounded-xl p-5 hover:shadow-md transition scroll-mt-24 flex flex-col md:flex-row gap-5 group">
                                    {{-- Vehicle Image --}}
                                    <div class="w-full md:w-48 h-32 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden relative">
                                        @if($booking->vehicle && $booking->vehicle->vehicle_image)
                                            <img src="{{ asset('images/' . $booking->vehicle->vehicle_image) }}" alt="{{ $booking->vehicle->model }}" class="w-full h-full object-contain bg-white group-hover:scale-105 transition duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                                <i class="ri-car-line text-4xl"></i>
                                            </div>
                                        @endif
                                        <div class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider text-gray-800 shadow-sm">
                                            {{ $booking->vehicle->plate_number ?? 'N/A' }}
                                        </div>
                                    </div>

                                    <div class="flex-1 flex flex-col justify-between">
                                        {{-- Header --}}
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h3 class="font-black text-gray-900 text-lg">Booking #{{ $booking->id }}</h3>
                                                     <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide
                                                        {{ $booking->status == 'Waiting for Verification' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}">
                                                        {{ $booking->status }}
                                                    </span>
                                                </div>
                                                <p class="text-sm font-bold text-gray-600 mt-0.5">{{ $booking->vehicle?->brand }} {{ $booking->vehicle?->model }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-gray-400 uppercase font-extrabold tracking-wider">Total</p>
                                                <p class="text-xl font-black text-gray-900">RM {{ number_format($booking->total_rental_fee + $booking->deposit_amount, 2) }}</p>
                                            </div>
                                        </div>

                                        {{-- Dates --}}
                                        <div class="grid grid-cols-2 gap-4 my-3 text-sm border-t border-b border-gray-50 py-3">
                                            <div>
                                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-0.5">Pickup</p>
                                                <p class="font-bold text-gray-700">{{ $booking->pickup_date_time ? \Carbon\Carbon::parse($booking->pickup_date_time)->format('d M Y, h:i A') : '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-0.5">Return</p>
                                                <p class="font-bold text-gray-700">{{ $booking->return_date_time ? \Carbon\Carbon::parse($booking->return_date_time)->format('d M Y, h:i A') : '-' }}</p>
                                            </div>
                                        </div>

                                        {{-- Fines Section --}}
                                        @if($booking->fines->count() > 0)
                                            <div class="mb-3 space-y-2 border-t border-dashed border-red-200 pt-3">
                                                @foreach($booking->fines as $fine)
                                                     <div class="flex items-center justify-between bg-red-50 p-2.5 rounded-lg border border-red-100 shadow-sm">
                                                         <div class="flex items-center gap-3">
                                                             <div class="flex items-center gap-1.5 text-red-700 border-r border-red-200 pr-3">
                                                                 <i class="ri-alarm-warning-fill text-lg"></i>
                                                                 <span class="text-xs font-bold uppercase tracking-wide">{{ $fine->reason }}</span>
                                                             </div>
                                                             <p class="text-red-700 font-black text-sm">
                                                                 RM {{ number_format($fine->amount, 2) }}
                                                             </p>
                                                         </div>
                                                         
                                                         @if($fine->status == 'Unpaid')
                                                             <button onclick="openFineModal('{{ route('fines.upload', $fine->id) }}', '{{ addslashes($fine->reason) }}', '{{ number_format($fine->amount, 2) }}')" 
                                                                     class="bg-red-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg shadow hover:bg-red-700 transition flex items-center gap-1 transform hover:scale-105">
                                                                 <i class="ri-secure-payment-line"></i> PAY NOW
                                                             </button>
                                                         @elseif($fine->status == 'Pending Verification')
                                                             <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-3 py-1 rounded border border-yellow-200 uppercase tracking-widest">
                                                                 Verifying
                                                             </span>
                                                         @else
                                                             <div class="flex items-center gap-2">
                                                                 <span class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1 rounded border border-green-200 uppercase tracking-widest">
                                                                     Paid
                                                                 </span>
                                                                 @if($fine->receipt_path)
                                                                     <a href="{{ Storage::url($fine->receipt_path) }}" target="_blank" 
                                                                        class="inline-flex items-center gap-2 bg-white text-green-700 text-sm font-bold px-4 py-2 rounded-lg border border-green-200 shadow-sm hover:bg-green-100 transition">
                                                                         <i class="ri-receipt-line text-lg"></i> View Receipt
                                                                     </a>
                                                                 @endif
                                                             </div>
                                                         @endif
                                                     </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Actions --}}
                                        <div class="flex justify-between items-end">
                                            <div class="flex flex-wrap gap-2">
                                                @if($booking->status == 'Approved')
                                                    @php
                                                        $now = \Carbon\Carbon::now();
                                                        $pickup = \Carbon\Carbon::parse($booking->pickup_date_time);
                                                        $hasPickup = $booking->inspections->where('type', 'pickup')->count() > 0;
                                                        $canModify = !$hasPickup && $now->lt($pickup);
                                                        $canCancel = !$hasPickup && $now->diffInHours($pickup, false) > 24;
                                                    @endphp
                                                    
                                                    @if($canModify)
                                                        <a href="{{ route('booking.edit', $booking->id) }}" class="text-xs text-white bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-lg transition font-bold flex items-center">
                                                            <i class="ri-edit-line mr-1"></i> Modify
                                                        </a>
                                                    @endif

                                                    @if($canCancel)
                                                        @php
                                                            $duration = ceil(\Carbon\Carbon::parse($booking->pickup_date_time)->floatDiffInHours(\Carbon\Carbon::parse($booking->return_date_time)));
                                                            $isStampBooking = ($booking->status === 'Approved' && $duration >= 3);
                                                            $confirmMsg = $isStampBooking 
                                                                ? "Are you sure you want to cancel? This booking has earned stamps, and cancelling it will REVOKE those stamps and any auto-claimed rewards."
                                                                : "Are you sure you want to cancel this booking?";
                                                        @endphp
                                                        <form action="{{ route('booking.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('{{ $confirmMsg }}');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-xs text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded-lg transition font-bold flex items-center">
                                                                <i class="ri-delete-bin-line mr-1"></i> Cancel
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($booking->inspections->where('type', 'pickup')->count() > 0)
                                                        <a href="{{ route('inspections.show', $booking->inspections->where('type', 'pickup')->first()) }}" class="text-xs text-white bg-blue-600 hover:bg-blue-500 px-3 py-1.5 rounded-lg transition font-bold flex items-center">
                                                            <i class="ri-eye-line mr-1"></i> Pickup Check
                                                        </a>

                                                        @if($booking->inspections->where('type', 'return')->count() > 0)
                                                            <a href="{{ route('inspections.show', $booking->inspections->where('type', 'return')->first()) }}" class="text-xs text-white bg-purple-600 hover:bg-purple-500 px-3 py-1.5 rounded-lg transition font-bold flex items-center">
                                                                <i class="ri-eye-line mr-1"></i> Return Check
                                                            </a>
                                                        @else
                                                            <a href="{{ route('inspections.create', ['booking' => $booking->id, 'type' => 'return']) }}" class="text-xs text-white bg-orange-600 hover:bg-orange-500 px-3 py-1.5 rounded-lg transition font-bold flex items-center">
                                                                <i class="ri-edit-circle-line mr-1"></i> Return Car
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('inspections.create', $booking) }}" class="text-xs text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-100 px-3 py-1.5 rounded-lg transition font-bold flex items-center">
                                                            <i class="ri-car-fill mr-1"></i> Start Pickup
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>

                                            {{-- Deposit Status for Ongoing --}}
                                            @if($booking->deposit_status === 'Returned')
                                                <div class="bg-green-50 border border-green-100 rounded-lg p-2.5 flex items-center gap-3">
                                                    <div class="text-green-700 flex items-center gap-1.5">
                                                        <i class="ri-checkbox-circle-fill text-lg"></i> 
                                                        <span class="text-xs font-bold uppercase tracking-wide">Deposit Returned</span>
                                                    </div>
                                                    @if($booking->deposit_receipt_path)
                                                        <a href="{{ Storage::url($booking->deposit_receipt_path) }}" target="_blank" 
                                                           class="text-sm bg-white border border-green-200 text-green-700 px-4 py-2 rounded-lg font-bold hover:bg-green-50 transition shadow-sm flex items-center gap-2">
                                                            <i class="ri-receipt-line text-lg"></i> View Receipt
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200 mb-10">
                            <p class="text-gray-500 text-sm">No ongoing bookings.</p>
                        </div>
                    @endif

                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="ri-history-line text-gray-400"></i> Past History
                    </h2>
                    
                    @if($pastBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($pastBookings as $booking)
                                <div id="booking-{{ $booking->id }}" class="border border-gray-100 bg-white rounded-xl p-5 hover:bg-gray-50 transition scroll-mt-24 flex flex-col md:flex-row gap-5 group">
                                    {{-- Vehicle Image --}}
                                    <div class="w-full md:w-48 h-32 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden transition duration-500 relative">
                                        @if($booking->vehicle && $booking->vehicle->vehicle_image)
                                            <img src="{{ asset('images/' . $booking->vehicle->vehicle_image) }}" alt="{{ $booking->vehicle->model }}" class="w-full h-full object-contain bg-white">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <i class="ri-car-line text-4xl"></i>
                                            </div>
                                        @endif
                                         <div class="absolute top-2 left-2 bg-black/50 text-white px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border border-white/20">
                                            {{ $booking->vehicle->plate_number ?? 'N/A' }}
                                        </div>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h3 class="font-bold text-gray-800 text-lg">Booking #{{ $booking->id }}</h3>
                                                     <span class="px-2 py-0.5 rounded text-[10px] font-bold border uppercase tracking-wider
                                                        @if($booking->status == 'Completed') bg-green-50 text-green-600 border-green-100
                                                        @elseif($booking->status == 'Rejected') bg-red-50 text-red-600 border-red-100
                                                        @else bg-gray-100 text-gray-600 @endif">
                                                        {{ $booking->status }}
                                                    </span>
                                                </div>
                                                <p class="text-xs font-bold text-gray-500 mt-1">{{ $booking->vehicle?->brand }} {{ $booking->vehicle?->model }}</p>
                                                <div class="mt-2 text-xs grid grid-cols-2 gap-x-4 gap-y-1">
                                                    <div>
                                                        <span class="text-gray-400 font-medium uppercase text-[10px]">Pickup</span>
                                                        <p class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($booking->pickup_date_time)->format('d M Y, h:i A') }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-400 font-medium uppercase text-[10px]">Return</span>
                                                        <p class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($booking->return_date_time)->format('d M Y, h:i A') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="text-right">
                                                 <p class="text-sm font-bold text-gray-800">RM {{ number_format($booking->total_rental_fee + $booking->deposit_amount, 2) }}</p>
                                            </div>
                                        </div>

                                        @if($booking->status == 'Rejected' && $booking->rejection_reason)
                                            <div class="bg-red-50 border border-red-100 rounded-lg p-3 my-2 text-xs text-red-700">
                                                <span class="font-bold">Reason:</span> {{ $booking->rejection_reason }}
                                            </div>
                                        @endif

                                        <div class="mt-3 flex flex-col items-end gap-2">
                                            
                                            {{-- Fines Display for Past --}}
                                             @if($booking->fines->count() > 0)
                                                @foreach($booking->fines as $fine)
                                                     <div class="bg-red-50 border border-red-100 rounded-lg p-2.5 inline-flex items-center gap-3 shadow-sm">
                                                         <div class="flex items-center gap-1.5 text-red-700 border-r border-red-200 pr-3">
                                                             <i class="ri-alarm-warning-fill text-lg"></i>
                                                             <span class="text-xs font-bold uppercase tracking-wide">{{ $fine->reason }}</span>
                                                         </div>
                                                         
                                                         <p class="text-red-700 font-black text-sm">
                                                             RM {{ number_format($fine->amount, 2) }}
                                                         </p>
                                                         
                                                         @if($fine->status == 'Unpaid')
                                                             <button onclick="openFineModal('{{ route('fines.upload', $fine->id) }}', '{{ addslashes($fine->reason) }}', '{{ number_format($fine->amount, 2) }}')" 
                                                                     class="bg-red-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg shadow hover:bg-red-700 transition flex items-center gap-1 transform hover:scale-105 ml-2">
                                                                 <i class="ri-secure-payment-line"></i> PAY NOW
                                                             </button>
                                                         @elseif($fine->status == 'Pending Verification')
                                                             <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-3 py-1 rounded border border-yellow-200 uppercase tracking-widest ml-2">
                                                                 Verifying
                                                             </span>
                                                         @else
                                                             <div class="flex items-center gap-2 ml-2">
                                                                 <span class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1 rounded border border-green-200 uppercase tracking-widest">
                                                                     Paid
                                                                 </span>
                                                                 @if($fine->receipt_path)
                                                                     <a href="{{ Storage::url($fine->receipt_path) }}" target="_blank" 
                                                                        class="inline-flex items-center gap-2 bg-white text-green-700 text-sm font-bold px-4 py-2 rounded-lg border border-green-200 shadow-sm hover:bg-green-100 transition">
                                                                         <i class="ri-receipt-line text-lg"></i> View Receipt
                                                                     </a>
                                                                 @endif
                                                             </div>
                                                         @endif
                                                     </div>
                                                @endforeach
                                            @endif
                                            @if($booking->deposit_status === 'Returned')
                                                <div class="bg-green-50 border border-green-100 rounded-lg p-2.5 inline-flex items-center gap-3">
                                                    <div class="flex items-center gap-1.5 text-green-700 border-r border-green-200 pr-3">
                                                        <i class="ri-checkbox-circle-fill text-lg"></i> 
                                                        <span class="text-xs font-bold uppercase tracking-wide">Deposit Returned</span>
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <p class="text-[10px] text-green-600 font-medium">
                                                            {{ \Carbon\Carbon::parse($booking->deposit_returned_at)->format('d M Y') }}
                                                        </p>
                                                        @if($booking->deposit_receipt_path)
                                                            <a href="{{ Storage::url($booking->deposit_receipt_path) }}" target="_blank" 
                                                               class="inline-flex items-center gap-2 bg-white text-green-700 text-sm font-bold px-4 py-2 rounded-lg border border-green-200 shadow-sm hover:bg-green-100 transition">
                                                                <i class="ri-receipt-line text-lg"></i> View Receipt
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @elseif($booking->status == 'Completed' && $booking->deposit_status != 'Returned')
                                                <div class="inline-flex items-center gap-1 text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg">
                                                    <i class="ri-time-line"></i> Deposit Processing
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-gray-400 text-sm">No history found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div id="content-rewards" class="hidden animate-fade-in">
                
                @php
                    $totalStamps = $totalStamps ?? 0; 
                    
                    $rewards = [
                        3 => '10% Off Voucher',
                        6 => '15% Off Voucher',
                        9 => '20% Off Voucher',
                        12 => '25% Off Voucher',
                        15 => '12 Hours Free Rental'
                    ];

                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    
                    {{-- LEFT COLUMN: LOYALTY CARD (LANDSCAPE) --}}
                    <div>
                        <div class="bg-[#fdfbf7] border border-stone-200 rounded-xl shadow-xl overflow-hidden relative transform transition hover:rotate-1 duration-500">
                            {{-- Card Header --}}
                            <div class="bg-[#2d3748] p-4 text-center border-b-4 border-[#ec5a29] relative overflow-hidden flex justify-between items-center px-6">
                                <div class="absolute top-0 left-0 w-full h-full opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 10px 10px;"></div>
                                
                                <div class="text-left">
                                    <h2 class="font-[Syncopate] font-bold text-lg text-white tracking-widest uppercase leading-none">Hasta</h2>
                                    <p class="text-[9px] text-gray-300 font-medium tracking-wide uppercase">Loyalty Card</p>
                                </div>
                                
                                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                                    <i class="ri-vip-crown-2-fill text-[#ec5a29]"></i>
                                </div>
                            </div>

                            {{-- Stamp Grid (Landscape: 5 Cols x 3 Rows) --}}
                            <div class="p-6 bg-[url('https://www.transparenttextures.com/patterns/cream-paper.png')]">
                                <div class="grid grid-cols-5 gap-4"> 
                                    @for ($i = 1; $i <= 15; $i++)
                                        @php
                                            $isMilestone = array_key_exists($i, $rewards);
                                            $hasStamp = $i <= $totalStamps;
                                            
                                            $displayRewards = [
                                                3 => '10% OFF',
                                                6 => '15% OFF',
                                                9 => '20% OFF',
                                                12 => '25% OFF',
                                                15 => 'FREE 12H'
                                            ];

                                            // Determine Icon and Color Logic
                                            if ($hasStamp) {
                                                // Earned States
                                                $textClass = 'text-white';
                                                if ($isMilestone) {
                                                    $icon = $i == 15 ? 'ri-trophy-fill' : 'ri-medal-fill';
                                                    $styleClass = 'bg-gradient-to-br from-yellow-300 to-yellow-500 border-yellow-600 shadow-lg';
                                                    $iconClass = 'text-white text-2xl drop-shadow-sm';
                                                } else {
                                                    $icon = 'ri-steering-2-fill';
                                                    $styleClass = 'bg-gradient-to-br from-[#ec5a29] to-[#d14a1e] border-[#ec5a29] shadow-inner';
                                                    $iconClass = 'text-white text-xl';
                                                }
                                            } else {
                                                // Unearned States
                                                if ($isMilestone) {
                                                    $textClass = 'text-[#ec5a29]';
                                                    $icon = $i == 15 ? 'ri-trophy-line' : 'ri-medal-line';
                                                    $styleClass = 'border-[#ec5a29] border-dashed bg-[#ec5a29]/5 animate-pulse';
                                                    $iconClass = 'text-[#ec5a29] opacity-40';
                                                } else {
                                                    $textClass = 'text-gray-300';
                                                    $icon = 'ri-steering-2-line';
                                                    $styleClass = 'border-gray-300 border-dashed bg-white/50';
                                                    $iconClass = 'text-gray-300';
                                                }
                                            }
                                        @endphp

                                        <div class="relative group aspect-square flex flex-col items-center justify-center rounded-full border-2 
                                            {{ $styleClass }}
                                            transition-all duration-300 hover:scale-110 cursor-default"
                                        >
                                            @if($hasStamp)
                                                {{-- Ring effect for earned stamps --}}
                                                <div class="absolute inset-1 rounded-full border border-white/20 pointer-events-none"></div>
                                                <i class="{{ $icon }} {{ $iconClass }} transform -rotate-12"></i>
                                            @elseif($isMilestone)
                                                <i class="{{ $icon }} text-2xl mb-1 {{ $iconClass }}"></i>
                                                <span class="font-bold text-[10px] uppercase leading-none text-center px-1 {{ $textClass }}">
                                                    {{ $displayRewards[$i] }}
                                                </span>
                                            @else
                                                <i class="{{ $icon }} {{ $iconClass }} text-xs opacity-50 mb-0.5"></i>
                                                <span class="font-bold text-xs {{ $textClass }}">
                                                    {{ $i }}
                                                </span>
                                            @endif

                                            @if($isMilestone)
                                                @if(!$hasStamp)
                                                    <div class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-[#ec5a29] rounded-full animate-ping"></div>
                                                    <div class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-[#ec5a29] rounded-full"></div>
                                                @else
                                                    {{-- Redemption is now Automatic --}}
                                                    <div class="absolute inset-0 z-20 flex items-center justify-center pointer-events-none">
                                                        <span class="bg-black/80 text-white text-[8px] font-bold uppercase text-center leading-tight tracking-wider px-2 py-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                                            Auto<br>Claim
                                                        </span>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            
                            {{-- Card Footer --}}
                            <div class="bg-gray-50 p-3 text-center border-t border-gray-100 flex justify-between items-center px-6">
                                <div class="text-left">
                                    <p class="text-[9px] text-gray-400 uppercase font-bold tracking-wider">Stamp Progress</p>
                                    <div class="w-32 h-1.5 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                        <div class="h-full bg-[#ec5a29]" style="width: {{ min(100, ($totalStamps / 15) * 100) }}%"></div>
                                    </div>
                                </div>
                                <div class="text-right">
                                     <p class="text-[#ec5a29] font-[Syncopate] font-bold text-xl leading-none">{{ $totalStamps }}<span class="text-gray-300 text-xs">/15</span></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4 text-gray-400 text-[10px]">
                            <p><i class="ri-information-line"></i> Stamps are automatically added upon booking completion.</p>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: VOUCHER HUB --}}
                    <div class="space-y-6">
                        
                        {{-- My Vouchers Panel --}}
                        <div class="bg-white rounded-xl shadow-sm border border-l-4 border-gray-100 border-l-[#ec5a29] p-6">
                             <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                                <i class="ri-coupon-3-line text-[#ec5a29]"></i> My Vouchers
                             </h3>
                             
                             <div class="space-y-3 max-h-[180px] overflow-y-auto pr-2 custom-scrollbar">
                                @forelse($myVouchers as $userVoucher)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 border-dashed group hover:border-[#ec5a29] transition-colors cursor-pointer relative overflow-hidden">
                                        <div class="absolute top-0 right-0 p-1">
                                            <div class="w-16 h-16 bg-[#ec5a29]/5 rounded-full -mr-8 -mt-8"></div>
                                        </div>
                                        
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm">{{ $userVoucher->voucher->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-mono tracking-wider mt-0.5">CODE: {{ $userVoucher->voucher->code }}</p>
                                            <p class="text-[9px] text-red-400 mt-1">{{ $userVoucher->voucher->type == 'percent' ? $userVoucher->voucher->value . '% Off' : 'RM ' . $userVoucher->voucher->value . ' Off' }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-gray-400 bg-gray-50 rounded-lg border border-dashed">
                                        <p class="text-xs">No active vouchers.</p>
                                    </div>
                                @endforelse
                             </div>
                        </div>

                        {{-- Redeem Voucher Panel --}}
                        <div class="bg-gray-800 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
                            
                            <h3 class="font-bold text-white text-md mb-2 flex items-center gap-2 relative z-10">
                                <i class="ri-gift-line text-[#ec5a29]"></i> Redeem Reward
                            </h3>
                            <p class="text-gray-400 text-xs mb-4 relative z-10">Enter your code to claim special rewards.</p>
                            
                            <div class="relative z-10">
                                <form action="{{ route('vouchers.redeem.code') }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="text" name="code" placeholder="ENTER VOUCHER CODE" required
                                        class="bg-gray-700/50 border border-gray-600 text-white text-sm rounded-lg focus:ring-[#ec5a29] focus:border-[#ec5a29] block w-full p-2.5 placeholder-gray-500 uppercase tracking-wider">
                                    <button type="submit" class="bg-[#ec5a29] hover:bg-[#d14a1e] text-white font-bold rounded-lg text-sm px-4 py-2 transition shadow-lg shadow-orange-900/20">
                                        Redeem
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div id="content-personal" class="hidden animate-fade-in space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    @include('profile.partials.update-password-form')
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 opacity-70 hover:opacity-100 transition">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <div id="content-notifications" class="hidden animate-fade-in">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-gray-800">All Notifications</h2>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <a href="#" class="text-xs text-[#ec5a29] font-bold hover:underline">Mark all as read</a>
                        @endif
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse(Auth::user()->notifications as $notification)
                            <a href="{{ route('profile.edit', ['tab' => 'booking']) }}#booking-{{ $notification->data['booking_id'] ?? '' }}" 
                               class="block p-5 flex gap-4 hover:bg-gray-50 transition {{ !$notification->read_at ? 'bg-blue-50/30' : '' }}"
                               onclick="jumpToBooking(event, 'booking-{{ $notification->data['booking_id'] ?? '' }}')">
                                
                                <div class="mt-1 flex-shrink-0">
                                    @if(isset($notification->data['status']) && $notification->data['status'] == 'Approved')
                                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><i class="ri-check-line text-lg"></i></div>
                                    @elseif(isset($notification->data['status']) && $notification->data['status'] == 'Rejected')
                                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center"><i class="ri-close-line text-lg"></i></div>
                                    @elseif(isset($notification->data['type']) && $notification->data['type'] == 'success')
                                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><i class="ri-refund-2-line text-lg"></i></div>
                                    @elseif(isset($notification->data['type']) && $notification->data['type'] == 'fine')
                                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center"><i class="ri-money-dollar-circle-line text-lg"></i></div>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="ri-notification-line text-lg"></i></div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900">{{ $notification->data['message'] ?? 'Notification' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->format('M d, Y h:i A') }} ({{ $notification->created_at->diffForHumans() }})</p>
                                </div>
                                @if(!$notification->read_at)
                                    <div class="w-2 h-2 bg-[#ec5a29] rounded-full mt-2"></div>
                                    {{ $notification->markAsRead() }}
                                @endif
                            </a>
                        @empty
                            <div class="p-12 text-center text-gray-400">
                                <i class="ri-notification-off-line text-4xl mb-3 block opacity-50"></i>
                                No notifications found.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

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

    {{-- Fine Payment Modal --}}
    <div id="finePaymentModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeFineModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="finePaymentForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="ri-wallet-3-line text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 flex items-center gap-2" id="modal-title">
                                    Pay Fine / Penalty
                                </h3>
                                <div class="mt-4 text-sm text-gray-500 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                    <div class="flex justify-between mb-2">
                                        <span>Reason:</span>
                                        <span id="fineReason" class="font-bold text-gray-800 text-right w-2/3"></span>
                                    </div>
                                    <div class="flex justify-between mb-4 border-b border-gray-200 pb-2">
                                        <span>Amount:</span>
                                        <span id="fineAmount" class="font-black text-red-600 text-lg"></span>
                                    </div>
                                    
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Upload Payment Receipt</label>
                                    <input type="file" name="receipt" required accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition border border-gray-300 rounded-lg">
                                    <p class="text-[10px] text-gray-400 mt-1">Accepted formats: JPG, PNG. Max size: 5MB.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none sm:w-auto sm:text-sm uppercase tracking-wider">
                            Submit Payment
                        </button>
                        <button type="button" onclick="closeFineModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm uppercase tracking-wider">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function openFineModal(url, reason, amount) {
            document.getElementById('finePaymentForm').action = url;
            document.getElementById('fineReason').textContent = reason;
            document.getElementById('fineAmount').textContent = 'RM ' + amount;
            document.getElementById('finePaymentModal').classList.remove('hidden');
        }
        function closeFineModal() {
            document.getElementById('finePaymentModal').classList.add('hidden');
        }
    </script>

    <script>
        function openTab(tabName) {
            const tabs = ['booking', 'rewards', 'personal', 'notifications'];
            const activeClass = "shadow-sm bg-[#ec5a29] text-white";
            const inactiveClass = "text-gray-500 hover:bg-gray-50";

            tabs.forEach(t => {
                // Hide content
                document.getElementById('content-' + t).classList.add('hidden');
                
                // Reset button style
                const btn = document.getElementById('tab-' + t);
                btn.className = btn.className.replace(activeClass, "").replace(inactiveClass, ""); // Clear classes
                btn.classList.add(...inactiveClass.split(" ")); // Add inactive
            });

            // Show selected
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Activate button
            const activeBtn = document.getElementById('tab-' + tabName);
            activeBtn.className = activeBtn.className.replace(inactiveClass.split(" ")[0], "").replace(inactiveClass.split(" ")[1], ""); // Remove inactive
            activeBtn.classList.add(...activeClass.split(" "));
        }

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            const hash = window.location.hash;

            if (tabParam === 'notifications') {
                openTab('notifications');
            } else if (tabParam === 'rewards') {
                openTab('rewards');
            } else if ("{{ session('status') }}" === 'profile-updated' || "{{ $errors->any() }}") {
                openTab('personal');
            } else if (tabParam === 'booking' || hash.startsWith('#booking-')) {
                openTab('booking');
                // Manually scroll if hash exists, because element was hidden during page load
                if (hash) {
                    setTimeout(() => {
                        const el = document.querySelector(hash);
                        if (el) {
                            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            // Highlight effect
                            el.classList.add('ring-2', 'ring-[#ec5a29]', 'ring-offset-2');
                            setTimeout(() => el.classList.remove('ring-2', 'ring-[#ec5a29]', 'ring-offset-2'), 2000);
                        }
                    }, 100);
                }
            } else {
                openTab('booking');
            }
        });

        // Listen for hash changes to handle same-page navigation (e.g. clicking notification while already on profile)
        window.addEventListener('hashchange', function() {
            const hash = window.location.hash;
            if (hash.startsWith('#booking-')) {
                openTab('booking');
                setTimeout(() => {
                    const el = document.querySelector(hash);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        el.classList.add('ring-2', 'ring-[#ec5a29]', 'ring-offset-2');
                        setTimeout(() => el.classList.remove('ring-2', 'ring-[#ec5a29]', 'ring-offset-2'), 2000);
                    }
                }, 100);
            }
        });

        function jumpToBooking(event, elementId) {
            event.preventDefault();
            openTab('booking');
            
            // Update URL without reload
            history.pushState(null, null, '#' + elementId);

            setTimeout(() => {
                const el = document.getElementById(elementId);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    el.classList.add('ring-2', 'ring-[#ec5a29]', 'ring-offset-2');
                    setTimeout(() => el.classList.remove('ring-2', 'ring-[#ec5a29]', 'ring-offset-2'), 2000);
                }
            }, 100);
        }
    </script>
</body>
</html>
