<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <title>All Bookings - Hasta Admin</title>
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
                    <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta Logo" class="w-32 mx-auto rounded-lg shadow-sm">
                </a>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="block py-3 px-4 rounded text-white transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 shadow-inner font-medium' : 'hover:bg-white/10' }}">
                    <i class="ri-dashboard-line mr-2"></i> Dashboard
                </a>

                <a href="{{ route('admin.bookings.index') }}" 
                   class="block py-3 px-4 rounded text-white transition {{ request()->routeIs('admin.bookings*') ? 'bg-white/20 shadow-inner font-medium' : 'hover:bg-white/10' }}">
                    <i class="ri-list-check-2 mr-2"></i> Bookings
                </a>

                <a href="{{ route('admin.vehicle.index') }}" 
                   class="block py-3 px-4 rounded text-white transition {{ request()->routeIs('admin.vehicle*') ? 'bg-white/20 shadow-inner font-medium' : 'hover:bg-white/10' }}">
                    <i class="ri-car-line mr-2"></i> Fleet Management
                </a>
    
                <a href="{{ route('admin.customers.index') }}" 
                   class="block py-3 px-4 rounded text-white transition {{ request()->routeIs('admin.customers*') ? 'bg-white/20 shadow-inner font-medium' : 'hover:bg-white/10' }}">
                    <i class="ri-group-line mr-2"></i> Customers
                </a>

                @if(Auth::user()->isTopManagement())
                <a href="{{ route('admin.reports') }}" 
                   class="block py-3 px-4 rounded text-white transition {{ request()->routeIs('admin.reports') ? 'bg-white/20 shadow-inner font-medium' : 'hover:bg-white/10' }}">
                     <i class="ri-file-chart-line mr-2"></i> Reports
                </a>
                @endif

                <a href="{{ route('admin.vouchers.index') }}" class="block py-3 px-4 rounded hover:bg-white/10 text-white transition {{ request()->routeIs('admin.vouchers.*') ? 'bg-white/20 shadow-inner' : '' }}">
                    <i class="ri-ticket-line mr-2"></i> Vouchers
                </a>
            </nav>

            <div class="p-4 border-t border-white/20">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full py-2 bg-white text-[#cb5c55] rounded hover:bg-gray-100 transition font-bold shadow-md">
                        <i class="ri-logout-box-line mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-8 ml-64">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">All Bookings</h2>
                    <p class="text-gray-500 text-sm">Full history of rental transactions</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
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
                            @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 font-medium text-gray-500">#{{ $booking->id }}</td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->user->phone_number }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="font-medium">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</div>
                                    <div class="text-xs text-gray-400">{{ $booking->vehicle->plate_number }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="text-xs">
                                        <div class="text-green-600">From: {{ \Carbon\Carbon::parse($booking->start_time)->format('d M, h:i A') }}</div>
                                        <div class="text-red-600">To: {{ \Carbon\Carbon::parse($booking->end_time)->format('d M, h:i A') }}</div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    @if($booking->status === 'Pending')
                                        <span class="px-3 py-1 text-xs font-bold text-gray-500 bg-gray-100 rounded-full border border-gray-200">Not Paid</span>
                                    @elseif($booking->status === 'Waiting for Verification')
                                        <span class="px-3 py-1 text-xs font-bold text-blue-700 bg-blue-100 rounded-full border border-blue-200 animate-pulse">Verify Receipt</span>
                                    @elseif($booking->status === 'Approved')
                                        <span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full border border-green-200">Approved</span>
                                    @elseif($booking->status === 'Rejected')
                                        <span class="px-3 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full border border-red-200">Rejected</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold text-gray-700 bg-gray-100 rounded-full">{{ $booking->status }}</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if($booking->status === 'Waiting for Verification')
                                        <a href="{{ route('admin.payment.verify', $booking->id) }}" class="inline-block px-3 py-1 theme-bg text-white rounded text-xs font-bold hover:opacity-90">
                                            Verify
                                        </a>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t">
                    {{ $bookings->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>