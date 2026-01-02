<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings - Hasta Car Rental</title>
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
        <nav class="flex-1 overflow-y-auto py-6 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                <i class="ri-dashboard-line mr-3 text-lg"></i> Dashboard
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.bookings*') ? 'sidebar-active' : '' }}">
                <i class="ri-list-check mr-3 text-lg"></i> Bookings
            </a>
            <a href="{{ route('admin.vehicle.index') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.vehicle*') ? 'sidebar-active' : '' }}">
                <i class="ri-car-line mr-3 text-lg"></i> Fleet Management
            </a>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.customers*') ? 'sidebar-active' : '' }}">
                <i class="ri-user-line mr-3 text-lg"></i> Customers
            </a>
            
            @if(Auth::user()->isTopManagement())
            <a href="{{ route('admin.reports') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.reports') ? 'sidebar-active' : '' }}">
                <i class="ri-file-chart-line mr-3 text-lg"></i> Reports
            </a>
            @endif
            
            <a href="{{ route('admin.vouchers.index') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.vouchers*') ? 'sidebar-active' : '' }}">
                <i class="ri-coupon-3-line mr-3 text-lg"></i> Vouchers
            </a>
        </nav>
        <div class="p-6 text-center text-[10px] text-white/30 uppercase tracking-widest font-bold">
            Hasta Admin Panel v1.1
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 z-10">
            <h2 class="text-2xl font-bold text-gray-800">All Bookings</h2>
            
            <div class="flex items-center gap-4">
                <div x-data="{ userMenu: false }" class="relative">
                    <button @click="userMenu = !userMenu" @click.away="userMenu = false" class="flex items-center gap-3 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-full border border-gray-200 transition focus:outline-none">
                        <div class="text-right hidden md:block">
                            <p class="text-xs font-bold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] text-green-500 font-bold uppercase tracking-wider">● Online</p>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-[#cb5c55] text-white flex items-center justify-center font-bold text-xs shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
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
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                    <h3 class="text-lg font-bold text-gray-800 tracking-tight">Full Booking History</h3>
                </div>
                
                <div class="overflow-x-auto text-left">
                    <table class="w-full text-sm text-gray-600">
                        <thead class="bg-gray-50 text-[10px] uppercase text-gray-400 font-black tracking-widest border-b">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Customer</th>
                                <th class="px-6 py-4">Vehicle</th>
                                <th class="px-6 py-4">Dates</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-400 text-xs">#{{ $booking->id }}</td>
                                
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.customers.show', $booking->user->id) }}" class="font-bold text-gray-800 hover:text-[#cd5c5c] hover:underline transition">
                                        {{ $booking->user->name }}
                                    </a>
                                    <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                                        {{ $booking->user->phone_number ?? $booking->user->phone ?? 'No Phone' }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800 uppercase">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono">{{ $booking->vehicle->plate_number }}</p>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded w-fit">
                                            From: {{ \Carbon\Carbon::parse($booking->start_time ?? $booking->start_date)->format('d M, h:i A') }}
                                        </span>
                                        <span class="text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded w-fit">
                                            To: {{ \Carbon\Carbon::parse($booking->end_time ?? $booking->end_date)->format('d M, h:i A') }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 uppercase tracking-tighter">
                                    @php
                                        $style = match($booking->status) {
                                            'Approved' => 'bg-green-100 text-green-700 border-green-200',
                                            'Rejected' => 'bg-red-100 text-red-700 border-red-200',
                                            'Waiting for Verification', 'Verify Receipt' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'Completed' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'Pending', 'pending' => 'bg-gray-100 text-gray-500 border-gray-200',
                                            default => 'bg-gray-100 text-gray-400'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold border {{ $style }} uppercase tracking-wider">
                                        {{ $booking->status == 'Waiting for Verification' ? 'Verify Receipt' : $booking->status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($booking->status == 'Waiting for Verification' || $booking->status == 'Verify Receipt')
                                        <a href="{{ route('admin.payment.verify', $booking->id) }}" class="inline-block bg-[#cd5c5c] text-white text-[10px] font-bold px-4 py-1.5 rounded-md shadow-sm hover:bg-[#b04a45] transition uppercase tracking-wider">Verify</a>
                                    
                                    @elseif($booking->status == 'Approved')
                                        <form action="{{ route('admin.booking.return', $booking->id) }}" method="POST" onsubmit="return confirm('Confirm vehicle return?')">
                                            @csrf
                                            <button type="submit" class="bg-green-100 text-green-700 border border-green-200 hover:bg-green-200 font-bold text-[10px] px-3 py-1.5 rounded-md transition flex items-center gap-1 mx-auto uppercase shadow-sm">
                                                <i class="ri-checkbox-circle-line"></i> Confirm Return
                                            </button>
                                        </form>
                                    
                                    @elseif($booking->status == 'Completed')
                                        <div class="flex items-center justify-center text-purple-300 gap-1 font-bold text-[10px] uppercase">
                                            <i class="ri-checkbox-circle-fill text-lg"></i> Done
                                        </div>
                                    
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-100">
                    {{ $bookings->links() }}
                </div>
            </div>

            <div class="mt-8 text-center text-xs text-gray-400">&copy; 2026 Hasta Car Rental Admin Panel</div>
        </main>
    </div>
</body>
</html>