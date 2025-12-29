<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin Dashboard - Hasta</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");
        body { font-family: 'Poppins', sans-serif; }
        .theme-text { color: #cb5c55; }
        .theme-bg { background-color: #cb5c55; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex">
        <aside class="w-64 bg-[#cb5c55] text-white flex flex-col fixed h-full shadow-lg z-50">
            
            <div class="p-6 text-center border-b border-white/20">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logo_hasta.jpeg') }}" 
                         alt="Hasta Logo" 
                         class="w-32 mx-auto rounded-lg shadow-sm"
                    >
                </a>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block py-3 px-4 rounded hover:bg-white/10 text-white transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 shadow-inner' : '' }}">
                <i class="ri-dashboard-line mr-2"></i> Dashboard
            </a>

            <a href="{{ route('admin.bookings.index') }}" class="block py-3 px-4 rounded hover:bg-white/10 text-white transition">
                <i class="ri-list-check-2 mr-2"></i> Bookings
            </a>

            <a href="{{ route('admin.vehicle.index') }}" class="block py-3 px-4 rounded hover:bg-white/10 text-white transition {{ request()->routeIs('admin.vehicle.*') ? 'bg-white/20 shadow-inner' : '' }}">
                <i class="ri-car-line mr-2"></i> Fleet Management
            </a>
    
            <a href="{{ route('admin.customers.index') }}" class="block py-3 px-4 rounded hover:bg-white/10 text-white transition {{ request()->routeIs('admin.customers.*') ? 'bg-white/20 shadow-inner' : '' }}">
                <i class="ri-group-line mr-2"></i> Customers
            </a>

            <a href="#" class="block py-3 px-4 rounded hover:bg-white/10 text-white transition">
                <i class="ri-file-chart-line mr-2"></i> Reports
            </a>
            </nav>

            <div class="p-4 border-t border-white/20">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full py-2 bg-white text-[#ec5a29] rounded hover:bg-gray-100 transition font-bold shadow-md">
                        <i class="ri-logout-box-line mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-8 ml-64">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Admin Dashboard</h2>
                    <p class="text-gray-500 text-sm">Manage bookings and verifications</p>
                </div>
                
                <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-full shadow-sm">
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-green-500">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-[#ec5a29] rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-400 flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pending</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</h3>
                        <p class="text-xs text-yellow-600 mt-1">Verifications Needed</p>
                    </div>
                    <div class="p-3 bg-yellow-100 text-yellow-600 rounded-lg">
                        <i class="ri-time-line text-2xl"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Active</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $stats['active'] ?? 0 }}</h3>
                        <p class="text-xs text-blue-600 mt-1">Cars on the road</p>
                    </div>
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                        <i class="ri-roadster-line text-2xl"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#ec5a29] flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Revenue</p>
                        <h3 class="text-3xl font-bold text-gray-800">RM {{ number_format($stats['revenue'] ?? 0, 2) }}</h3>
                        <p class="text-xs theme-text mt-1">Total Verified Income</p>
                    </div>
                    <div class="p-3 bg-orange-100 theme-text rounded-lg">
                        <i class="ri-money-dollar-circle-line text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Recent Bookings</h3>
                        <p class="text-xs text-gray-400">Latest transactions and rental requests</p>
                    </div>
                    <button class="text-sm theme-text font-medium hover:underline">View All</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase font-bold tracking-wider">
                                <th class="p-4 border-b">ID</th>
                                <th class="p-4 border-b">Customer</th>
                                <th class="p-4 border-b">Vehicle</th>
                                <th class="p-4 border-b">Dates</th>
                                <th class="p-4 border-b">Status</th>
                                <th class="p-4 border-b text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($bookings as $booking)
                            <tr class="hover:bg-orange-50 transition">
                                <td class="p-4 font-medium text-gray-500">#{{ $booking->id }}</td>
                                
                                <td class="p-4">
                                    <p class="font-bold text-gray-800">{{ $booking->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $booking->user->phone_number }}</p>
                                </td>
                                
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded overflow-hidden bg-gray-100">
                                            <img src="{{ asset('images/' . $booking->vehicle->vehicle_image) }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <span class="font-medium block">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</span>
                                            <span class="text-xs text-gray-400">{{ $booking->vehicle->plate_number }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-4 text-gray-600">
                                    <div class="flex flex-col text-xs">
                                        <span><i class="ri-arrow-right-line text-green-500"></i> {{ \Carbon\Carbon::parse($booking->start_time)->format('d M, h:i A') }}</span>
                                        <span><i class="ri-arrow-left-line text-red-500"></i> {{ \Carbon\Carbon::parse($booking->end_time)->format('d M, h:i A') }}</span>
                                    </div>
                                </td>

                                <td class="p-4">
                                    @if($booking->status === 'Pending')
                                        <span class="px-3 py-1 text-xs font-bold text-gray-500 bg-gray-100 rounded-full border border-gray-200">Not Paid</span>
                                    @elseif($booking->status === 'Waiting for Verification')
                                        <span class="px-3 py-1 text-xs font-bold text-blue-700 bg-blue-100 rounded-full border border-blue-200 animate-pulse">
                                            <i class="ri-loader-2-line"></i> Verify Receipt
                                        </span>
                                    @elseif($booking->status === 'Approved')
                                        <span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full border border-green-200">
                                            <i class="ri-check-line"></i> Approved
                                        </span>
                                    @elseif($booking->status === 'Rejected')
                                        <span class="px-3 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full border border-red-200">Rejected</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold text-gray-700 bg-gray-100 rounded-full">{{ $booking->status }}</span>
                                    @endif
                                </td>

                                <td class="p-4 text-center">
                                    @if($booking->status === 'Waiting for Verification')
                                        <a href="{{ route('admin.payment.verify', $booking->id) }}" class="inline-flex items-center px-4 py-2 theme-bg text-white rounded-md text-xs font-bold shadow-md hover:shadow-lg hover:opacity-90 transition">
                                            <i class="ri-eye-line mr-1"></i> Verify
                                        </a>
                                    @elseif($booking->status === 'Approved')
                                        <button class="text-gray-300 cursor-default" title="Already Approved">
                                            <i class="ri-checkbox-circle-fill text-2xl text-green-500"></i>
                                        </button>
                                    @else
                                        <span class="text-gray-300 text-xs italic">--</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-400 bg-gray-50">
                                    <i class="ri-inbox-line text-4xl mb-2 block"></i>
                                    No bookings found yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

</body>
</html>