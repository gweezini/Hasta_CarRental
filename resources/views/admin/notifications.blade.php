<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Center - Hasta Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; }
        .sidebar-link-active { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid #fff; }
    </style>
</head>
<body class="flex h-screen overflow-hidden" x-data="{ currentTab: 'active' }">

    <aside class="w-64 bg-[#cb5c55] text-white flex flex-col flex-shrink-0 transition-all duration-300">
        <div class="p-6 flex items-center justify-center border-b border-white/10">
            <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Logo" class="h-10 rounded shadow-lg border-2 border-white/30">
        </div>

        <nav class="flex-1 overflow-y-auto py-6 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition">
                <i class="ri-dashboard-line mr-3 text-lg"></i> Dashboard
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition">
                <i class="ri-list-check mr-3 text-lg"></i> Bookings
            </a>
            <a href="{{ route('admin.vehicle.index') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition">
                <i class="ri-car-line mr-3 text-lg"></i> Fleet Management
            </a>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition">
                <i class="ri-user-line mr-3 text-lg"></i> Customers
            </a>
            
            @if(Auth::user()->isTopManagement())
            <a href="{{ route('admin.reports') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition">
                <i class="ri-file-chart-line mr-3 text-lg"></i> Reports
            </a>
            @endif

            <a href="{{ route('admin.vouchers.index') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition">
                <i class="ri-coupon-3-line mr-3 text-lg"></i> Vouchers
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 bg-white text-[#cb5c55] rounded-lg font-bold hover:bg-gray-100 transition shadow-sm">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-[#cb5c55] transition">
                    <i class="ri-arrow-left-line text-2xl align-middle"></i>
                </a>
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">Notification Center</h2>
            </div>
            
            <div class="flex items-center gap-3 bg-gray-50 px-4 py-2 rounded-full border border-gray-200">
                <div class="text-right hidden md:block">
                    <p class="text-xs font-bold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-[9px] text-green-500 font-bold uppercase tracking-widest">Online</p>
                </div>
                <div class="h-8 w-8 rounded-full bg-[#cb5c55] text-white flex items-center justify-center font-bold text-xs shadow-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-[#f3f4f6]">
            <div class="max-w-5xl mx-auto">
                
                <div class="flex p-1 bg-gray-200 rounded-xl w-fit mb-10 shadow-inner">
                    <button @click="currentTab = 'active'" 
                            :class="currentTab === 'active' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            class="px-12 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-3">
                        Active Alerts
                        @if($activeList->count() > 0)
                            <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $activeList->count() }}</span>
                        @endif
                    </button>
                    <button @click="currentTab = 'history'" 
                            :class="currentTab === 'history' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            class="px-12 py-2.5 rounded-lg text-sm font-bold transition-all">
                        Processed Records
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden min-h-[450px]">
                    
                    <div x-show="currentTab === 'active'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0">
                        <div class="divide-y divide-gray-100">
                            @forelse($activeList as $notif)
                                <a href="{{ route('admin.vehicle.edit', $notif->car_id) }}" class="block p-8 hover:bg-gray-50 transition group border-l-4 border-transparent hover:border-red-500">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-[#cb5c55] transition leading-tight">{{ $notif->message }}</h4>
                                            <div class="mt-3 flex items-center gap-6 text-sm">
                                                <span class="text-gray-500 uppercase tracking-tighter font-medium text-xs">Expiry Date: <span class="text-gray-800 font-bold ml-1">{{ \Carbon\Carbon::parse($notif->date)->format('d M Y') }}</span></span>
                                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $notif->is_expired ? 'bg-red-50 text-red-600' : 'bg-orange-50 text-orange-600' }}">
                                                    {{ strtoupper(\Carbon\Carbon::parse($notif->date)->diffForHumans()) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col items-end gap-3">
                                            <span class="text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded {{ $notif->is_expired ? 'bg-red-600 text-white shadow-sm' : 'bg-yellow-400 text-black shadow-sm' }}">
                                                {{ $notif->is_expired ? 'Action Required' : 'Pending' }}
                                            </span>
                                            <i class="ri-arrow-right-line text-gray-300 group-hover:text-[#cb5c55] group-hover:translate-x-1 transition text-xl mt-2"></i>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="py-40 text-center">
                                    <h3 class="text-lg font-bold text-gray-300 uppercase tracking-widest">No Active Alerts</h3>
                                    <p class="text-gray-400 text-sm mt-2 font-medium">All vehicle documents are healthy.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'history'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" style="display: none;">
                        <div class="divide-y divide-gray-100">
                            @forelse($resolvedList as $notif)
                                <a href="{{ route('admin.vehicle.edit', $notif->car_id) }}" class="block p-8 hover:bg-gray-50 transition group opacity-60 hover:opacity-100 border-l-4 border-transparent hover:border-green-500">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium text-gray-500 line-through decoration-gray-300 tracking-tight">{{ $notif->message }}</h4>
                                            <div class="mt-3 flex items-center gap-4 text-xs text-gray-400 font-medium">
                                                <span class="uppercase tracking-tighter text-[10px]">Processed: <strong>{{ \Carbon\Carbon::parse($notif->date)->format('d M Y, h:i A') }}</strong></span>
                                                <span class="text-green-600 uppercase tracking-tighter text-[10px]">Verified</span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col items-end">
                                            <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded bg-green-50 text-green-700 border border-green-100">
                                                Resolved
                                            </span>
                                            <i class="ri-check-line text-green-500 text-xl mt-3"></i>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="py-40 text-center">
                                    <h3 class="text-lg font-bold text-gray-300 uppercase tracking-widest">No Processed Records</h3>
                                    <p class="text-gray-400 text-sm mt-2 font-medium">Archived logs will appear here.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <div class="mt-12 text-center">
                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em] font-semibold">
                        &copy; 2026 Hasta Car Rental • Administrative Audit System
                    </p>
                </div>

            </div>
        </main>
    </div>
</body>
</html>