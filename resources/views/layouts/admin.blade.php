<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; }
        .sidebar-active { background-color: rgba(255, 255, 255, 0.2); border-left: 4px solid #fff; }

        @media print {
            aside, header, button, form, .no-print {
                display: none !important;
            }
            main {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                overflow: visible !important;
                background-color: white !important;
            }
            .shadow-sm, .shadow-md, .shadow-lg, .shadow-2xl {
                box-shadow: none !important;
                border: 1px solid #eee !important;
            }
            .bg-[#cb5c55], .theme-bg, .bg-red-50, .bg-blue-50, .bg-green-50, .bg-purple-50 {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="flex min-h-screen bg-[#f3f4f6]">

    <aside class="w-64 bg-[#cb5c55] text-white flex flex-col flex-shrink-0 transition-all duration-300 sticky top-0 h-screen">
        <div class="p-6 flex items-center justify-center border-b border-white/10">
            <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Logo" class="h-10 rounded shadow-lg border-2 border-white/30">
        </div>

        <nav class="flex-1 overflow-y-auto py-8 space-y-2 custom-scrollbar">
    {{-- 1. Dashboard --}}
    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3.5 text-base font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
        <i class="ri-dashboard-line mr-3 text-xl"></i> Dashboard
    </a>

    <a href="{{ route('admin.bookings.index') }}" class="flex items-center px-6 py-3.5 text-base font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.bookings*') ? 'sidebar-active' : '' }}">
        <i class="ri-list-check mr-3 text-xl"></i> Bookings
    </a>

    <a href="{{ route('admin.vehicle.index') }}" class="flex items-center px-6 py-3.5 text-base font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.vehicle*') ? 'sidebar-active' : '' }}">
        <i class="ri-car-line mr-3 text-xl"></i> Fleet Management
    </a>

    <a href="{{ route('admin.customers.index') }}" class="flex items-center px-6 py-3.5 text-base font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.customers*') ? 'sidebar-active' : '' }}">
        <i class="ri-user-line mr-3 text-xl"></i> Customers
    </a>

    <a href="{{ route('admin.vouchers.index') }}" class="flex items-center px-6 py-3.5 text-base font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.vouchers*') ? 'sidebar-active' : '' }}">
        <i class="ri-coupon-3-line mr-3 text-xl"></i> Vouchers
    </a>

    <a href="{{ route('admin.feedbacks.index') }}" class="flex items-center px-6 py-3.5 text-base font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.feedbacks*') ? 'sidebar-active' : '' }}">
        <i class="ri-feedback-line mr-3 text-xl"></i> Feedbacks
    </a>

    @if(Auth::user()->isTopManagement())
    <div x-data="{ open: {{ request()->routeIs('admin.claims.*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-3.5 text-base font-medium hover:bg-white/10 transition focus:outline-none" :class="{'bg-white/10': open}">
            <div class="flex items-center">
                <i class="ri-money-dollar-circle-line mr-3 text-xl"></i> Claims
            </div>
            <i class="ri-arrow-down-s-line transition-transform duration-200" :class="{'rotate-180': open}"></i>
        </button>
        <div x-show="open" class="bg-black/10 text-sm">
            <a href="{{ route('admin.claims.create') }}" class="block pl-14 pr-6 py-2.5 hover:bg-white/5 transition {{ request()->routeIs('admin.claims.create') ? 'text-white font-bold' : 'text-white/70' }}">
                My Claims
            </a>
            <a href="{{ route('admin.claims.index') }}" class="block pl-14 pr-6 py-2.5 hover:bg-white/5 transition {{ request()->routeIs('admin.claims.index') ? 'text-white font-bold' : 'text-white/70' }}">
                Review Claims
            </a>
        </div>
    </div>
    @else
    <a href="{{ route('admin.claims.create') }}" class="flex items-center px-6 py-3.5 text-base font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.claims.create') ? 'sidebar-active' : '' }}">
        <i class="ri-money-dollar-circle-line mr-3 text-xl"></i> My Claims
    </a>
    @endif

    @if(Auth::user()->isTopManagement())
    <div x-data="{ open: {{ request()->routeIs('admin.reports') || request()->routeIs('admin.staff.*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-3.5 text-base font-medium hover:bg-white/10 transition focus:outline-none" :class="{'bg-white/10': open}">
            <div class="flex items-center">
                <i class="ri-bank-line mr-3 text-xl"></i> Finance Control
            </div>
            <i class="ri-arrow-down-s-line transition-transform duration-200" :class="{'rotate-180': open}"></i>
        </button>
        <div x-show="open" class="bg-black/10 text-sm">
            <a href="{{ route('admin.reports') }}" class="block pl-14 pr-6 py-2.5 hover:bg-white/5 transition {{ request()->routeIs('admin.reports') ? 'text-white font-bold' : 'text-white/70' }}">
                Reports
            </a>
            <a href="{{ route('admin.staff.index') }}" class="block pl-14 pr-6 py-2.5 hover:bg-white/5 transition {{ request()->routeIs('admin.staff*') ? 'text-white font-bold' : 'text-white/70' }}">
                Staff Payroll
            </a>
        </div>
    </div>
    @endif


</nav>
        
        <div class="p-6 text-center text-[10px] text-white/30 uppercase tracking-widest font-bold">
            Hasta Admin Panel v1.1
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-h-screen">
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 z-10 sticky top-0">
            <h2 class="text-2xl font-bold text-gray-800">
                @yield('header_title', 'Admin Portal')
            </h2>
            
            <div class="flex items-center gap-4">
                <div x-data="{ open: false }" class="relative">
                    @php
                        $alertCount = 0;
                        if(isset($roadTaxAlerts) && isset($insuranceAlerts)) {
                            $alertCount = $roadTaxAlerts->count() + $insuranceAlerts->count();
                        }
                        $unreadNotifications = Auth::user()->unreadNotifications;
                        $totalAlerts = $alertCount + $unreadNotifications->count();
                    @endphp
                    
                    <button @click="open = !open" @click.away="open = false" class="relative p-2 text-gray-400 hover:text-[#cd5c5c] transition focus:outline-none">
                        <i class="ri-notification-3-line text-2xl"></i>
                        @if($totalAlerts > 0)
                            <span class="absolute top-1 right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                            </span>
                        @endif
                    </button>
                    
                    <div x-show="open" style="display: none;" x-transition class="absolute right-0 mt-3 w-96 bg-white rounded-xl shadow-2xl border z-50 overflow-hidden text-left">
                        <div class="px-5 py-4 border-b bg-gray-50/50 font-bold text-base uppercase text-gray-700 flex justify-between items-center">
                            <span>Notifications</span>
                            @if($unreadNotifications->count() > 0)
                                <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $unreadNotifications->count() }} NEW</span>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            {{-- Database Notifications --}}
                            @foreach($unreadNotifications as $notification)
                                <div class="block px-5 py-4 border-b hover:bg-gray-50 transition relative">
                                    <div class="flex gap-3">
                                        <div class="h-8 w-8 rounded-full {{ $notification->data['category'] == 'claim_submission' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }} flex flex-shrink-0 items-center justify-center">
                                            <i class="{{ $notification->data['category'] == 'claim_submission' ? 'ri-refund-2-line' : 'ri-checkbox-circle-line' }}"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-black text-gray-900 leading-tight">{{ $notification->data['message'] }}</p>
                                            <p class="text-[10px] text-gray-400 mt-1 font-bold">{{ $notification->created_at->diffForHumans() }}</p>
                                            <a href="{{ $notification->data['url'] }}" class="inline-block mt-2 text-[10px] font-black text-[#cb5c55] uppercase tracking-widest hover:underline">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Vehicle Expiry Alerts --}}
                            @if($alertCount > 0)
                                @foreach($roadTaxAlerts as $car)
                                    <a href="{{ route('admin.vehicle.edit', $car->id) }}" class="block px-5 py-4 border-b hover:bg-red-50 transition">
                                        <p class="text-sm font-bold text-gray-800 uppercase">Road Tax: {{ $car->plate_number }}</p>
                                        <p class="text-xs text-red-500 mt-1 font-medium">{{ \Carbon\Carbon::parse($car->road_tax_expiry)->diffForHumans() }}</p>
                                    </a>
                                @endforeach
                                @foreach($insuranceAlerts as $car)
                                    <a href="{{ route('admin.vehicle.edit', $car->id) }}" class="block px-5 py-4 border-b hover:bg-orange-50 transition">
                                        <p class="text-sm font-bold text-gray-800 uppercase">Insurance: {{ $car->plate_number }}</p>
                                        <p class="text-xs text-orange-500 mt-1 font-medium">{{ \Carbon\Carbon::parse($car->insurance_expiry)->diffForHumans() }}</p>
                                    </a>
                                @endforeach
                            @endif

                            @if($totalAlerts == 0)
                                <div class="p-6 text-center text-gray-400">No new notifications</div>
                            @endif
                        </div>
                        @if($alertCount > 0)
                        <a href="{{ route('admin.notifications') }}" class="block text-center py-4 text-sm font-black text-[#cd5c5c] hover:bg-gray-50 border-t tracking-widest uppercase">
                            View All Notifications
                        </a>
                        @endif
                    </div>
                </div>

                <div x-data="{ userMenu: false }" class="relative">
                    <button @click="userMenu = !userMenu" @click.away="userMenu = false" class="flex items-center gap-3 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-full border border-gray-200 transition focus:outline-none">
                        <div class="text-right hidden md:block">
                            <p class="text-xs font-bold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] text-green-500 font-bold uppercase tracking-wider">● Online</p>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-[#cb5c55] text-white flex items-center justify-center font-bold text-xs shadow-sm overflow-hidden">
                            @if(Auth::user()->profile_photo_path) 
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="" class="h-full w-full object-cover">
                            @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
                        </div>
                    </button>
                    
                    <div x-show="userMenu" style="display: none;" x-transition class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-2xl border py-1 z-50">
                        <a href="{{ route('admin.profile') }}" class="block w-full flex items-center px-4 py-3 text-xs text-gray-700 font-bold hover:bg-gray-50 transition text-left">
                            <i class="ri-user-settings-line mr-2 text-base"></i> My Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 text-xs text-red-500 font-bold hover:bg-red-50 transition text-left">
                                <i class="ri-logout-box-r-line mr-2 text-base"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-8 bg-[#f3f4f6]">
            @yield('content')
            <div class="mt-8 text-center text-xs text-gray-400 no-print">&copy; 2026 Hasta Car Rental Admin Panel</div>
        </main>
    </div>

</body>
</html>