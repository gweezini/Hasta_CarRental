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
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#cb5c55] text-white flex flex-col flex-shrink-0 transition-all duration-300">
    <div class="p-6 flex items-center justify-center border-b border-white/10">
        <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Logo" class="h-10 rounded shadow-lg border-2 border-white/30">
    </div>

    <nav class="flex-1 overflow-y-auto py-8 space-y-2">
        
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
        
        @if(Auth::user()->isTopManagement())
        <a href="{{ route('admin.reports') }}" class="flex items-center px-6 py-3.5 text-base font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.reports') ? 'sidebar-active' : '' }}">
            <i class="ri-file-chart-line mr-3 text-xl"></i> Reports
        </a>
        @endif
        
        <a href="{{ route('admin.vouchers.index') }}" class="flex items-center px-6 py-3.5 text-base font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.vouchers*') ? 'sidebar-active' : '' }}">
            <i class="ri-coupon-3-line mr-3 text-xl"></i> Vouchers
        </a>
    </nav>
    
    <div class="p-6 text-center text-[10px] text-white/30 uppercase tracking-widest font-bold">
        Hasta Admin Panel v1.1
    </div>
</aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 z-10">
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
                    @endphp
                    
                    <button @click="open = !open" @click.away="open = false" class="relative p-2 text-gray-400 hover:text-[#cd5c5c] transition focus:outline-none">
                        <i class="ri-notification-3-line text-2xl"></i>
                        @if($alertCount > 0)
                            <span class="absolute top-1 right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                            </span>
                        @endif
                    </button>
                    
                    <div x-show="open" style="display: none;" x-transition class="absolute right-0 mt-3 w-96 bg-white rounded-xl shadow-2xl border z-50 overflow-hidden text-left">
                        <div class="px-5 py-4 border-b bg-gray-50/50 font-bold text-base uppercase text-gray-700">Expiry Alerts</div>
                        <div class="max-h-64 overflow-y-auto">
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
                            @else
                                <div class="p-6 text-center text-gray-400">
                                    No new notifications
                                </div>
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

        <main class="flex-1 overflow-y-auto p-8 bg-[#f3f4f6]">
            @yield('content')
            
            <div class="mt-8 text-center text-xs text-gray-400">&copy; 2026 Hasta Car Rental Admin Panel</div>
        </main>
    </div>

</body>
</html>