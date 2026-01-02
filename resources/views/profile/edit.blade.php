<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Hasta Car Rental</title>
    
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
                            <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition flex items-start gap-3">
                                <div class="mt-1">
                                    @if(isset($notification->data['status']) && $notification->data['status'] == 'Approved')
                                        <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><i class="ri-check-line text-xs"></i></div>
                                    @elseif(isset($notification->data['status']) && $notification->data['status'] == 'Rejected')
                                        <div class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center"><i class="ri-close-line text-xs"></i></div>
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
                <p class="text-sm text-gray-500 mt-1">Manage your bookings and personal information</p>
            </div>

            <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-100 inline-flex items-center mb-6 overflow-x-auto max-w-full">
                <button onclick="openTab('booking')" id="tab-booking" class="px-6 py-2.5 text-sm font-bold rounded-lg shadow-sm bg-[#ec5a29] text-white transition-all whitespace-nowrap mr-2">
                    Booking History
                </button>

                <button onclick="openTab('rewards')" id="tab-rewards" class="px-6 py-2.5 text-sm font-medium rounded-lg text-gray-500 hover:bg-gray-50 transition-all whitespace-nowrap mr-2">
                    My Rewards
                </button>

                <button onclick="openTab('personal')" id="tab-personal" class="px-6 py-2.5 text-sm font-medium rounded-lg text-gray-500 hover:bg-gray-50 transition-all whitespace-nowrap mr-2">
                    Personal Info
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

            <div id="content-booking" class="animate-fade-in">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 md:p-8">
                    
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="ri-car-line text-[#ec5a29]"></i> Ongoing Bookings
                    </h2>
                    
                    @if($ongoingBookings->count() > 0)
                        <div class="space-y-4 mb-10">
                            @foreach($ongoingBookings as $booking)
                                <div class="border border-blue-100 bg-blue-50/50 rounded-xl p-5 hover:shadow-md transition">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-2">
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-lg">Booking #{{ $booking->id }}</h3>
                                            <p class="text-sm text-gray-600">{{ $booking->vehicle?->brand }} {{ $booking->vehicle?->model }} ({{ $booking->vehicle?->plate_number }})</p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                            {{ $booking->status == 'Waiting for Verification' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $booking->status }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-6 text-sm">
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase font-bold">Pickup</p>
                                            <p class="font-medium text-gray-800">{{ $booking->pickup_date_time ? \Carbon\Carbon::parse($booking->pickup_date_time)->format('M d, Y h:i A') : '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase font-bold">Return</p>
                                            <p class="font-medium text-gray-800">{{ $booking->return_date_time ? \Carbon\Carbon::parse($booking->return_date_time)->format('M d, Y h:i A') : '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-blue-100 flex justify-between items-center">
                                        <div class="flex gap-2">
                                            @if($booking->status == 'Approved')
                                                @php
                                                    $now = \Carbon\Carbon::now();
                                                    $pickup = \Carbon\Carbon::parse($booking->pickup_date_time);
                                                    $canModify = $now->lt($pickup);
                                                    $canCancel = $now->diffInHours($pickup, false) > 24;
                                                @endphp
                                                
                                                @if($canModify)
                                                    <a href="{{ route('booking.edit', $booking->id) }}" class="text-xs md:text-sm text-white bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-lg transition font-medium flex items-center">
                                                        <i class="ri-edit-line mr-1"></i> Modify
                                                    </a>
                                                @endif

                                                @if($canCancel)
                                                    <form action="{{ route('booking.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking? If you have made a payment, please contact us for a refund.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs md:text-sm text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-2 rounded-lg transition font-medium flex items-center">
                                                            <i class="ri-delete-bin-line mr-1"></i> Cancel
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                        <p class="text-lg font-bold text-gray-900">RM {{ number_format($booking->total_rental_fee, 2) }}</p>
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
                                <div class="border border-gray-100 rounded-xl p-5 hover:bg-gray-50 transition">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-3 gap-2">
                                        <div>
                                            <h3 class="font-bold text-gray-800">Booking #{{ $booking->id }}</h3>
                                            <p class="text-xs text-gray-500">{{ $booking->vehicle?->model }}</p>
                                        </div>
                                        <span class="px-2 py-1 rounded text-xs font-bold border 
                                            @if($booking->status == 'Completed') bg-green-50 text-green-600 border-green-100
                                            @elseif($booking->status == 'Rejected') bg-red-50 text-red-600 border-red-100
                                            @else bg-gray-100 text-gray-600 @endif">
                                            {{ $booking->status }}
                                        </span>
                                    </div>
                                    <p class="text-sm font-bold text-right text-gray-800">RM {{ number_format($booking->total_rental_fee, 2) }}</p>
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
                                    <p class="text-[9px] text-gray-300 font-medium tracking-wide uppercase">Member Card</p>
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
                                        @endphp
                                        
                                        <div class="relative group aspect-square flex items-center justify-center rounded-full border-2 
                                            {{ $isMilestone ? 'border-[#ec5a29] border-dashed bg-[#ec5a29]/5' : 'border-gray-300 border-dashed bg-white/50' }}
                                            {{ $hasStamp ? 'bg-[#ec5a29] border-solid border-[#ec5a29] shadow-inner' : '' }}
                                            transition-all duration-300 hover:scale-110 cursor-default"
                                        >
                                            @if($hasStamp)
                                                <i class="ri-vip-crown-2-fill text-white text-xl drop-shadow-md transform -rotate-12"></i>
                                            @else
                                                <span class="font-[Syncopate] font-bold text-sm {{ $isMilestone ? 'text-[#ec5a29]' : 'text-gray-300' }}">
                                                    {{ $i }}
                                                </span>
                                            @endif

                                            @if($isMilestone)
                                                {{-- Tooltip --}}
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-900 text-white text-[9px] rounded opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none z-10 font-bold tracking-wide shadow-xl transform translate-y-2 group-hover:translate-y-0">
                                                    {{ $rewards[$i] }}
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                                
                                                @if(!$hasStamp)
                                                <div class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-[#ec5a29] rounded-full animate-ping"></div>
                                                <div class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-[#ec5a29] rounded-full"></div>
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
                                        <div class="h-full bg-[#ec5a29]" style="width: {{ ($totalStamps / 15) * 100 }}%"></div>
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
                            <div class="p-5 flex gap-4 hover:bg-gray-50 transition {{ !$notification->read_at ? 'bg-blue-50/30' : '' }}">
                                <div class="mt-1 flex-shrink-0">
                                    @if(isset($notification->data['status']) && $notification->data['status'] == 'Approved')
                                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><i class="ri-check-line text-lg"></i></div>
                                    @elseif(isset($notification->data['status']) && $notification->data['status'] == 'Rejected')
                                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center"><i class="ri-close-line text-lg"></i></div>
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
                            </div>
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

            if (tabParam === 'notifications') {
                openTab('notifications');
            } else if (tabParam === 'rewards') {
                openTab('rewards');
            } else if ("{{ session('status') }}" === 'profile-updated' || "{{ $errors->any() }}") {
                openTab('personal');
            } else {
                openTab('booking');
            }
        });
    </script>
</body>
</html>