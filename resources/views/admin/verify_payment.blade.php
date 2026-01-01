<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <title>Verify Payment - Hasta Admin</title>
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
            <div class="mb-6 flex items-center gap-2 text-gray-500 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="hover:underline hover:text-[#cb5c55]">
                    <i class="ri-arrow-left-line"></i> Back to Dashboard
                </a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Verify Payment #{{ $booking->id }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="bg-white p-6 rounded-xl shadow-sm col-span-2 h-fit border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Booking Information</h3>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Customer</p>
                            <p class="font-semibold text-gray-800 text-lg">{{ $booking->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->user->phone_number }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 uppercase font-bold">Current Status</p>
                            <span class="inline-block mt-1 px-4 py-1 text-sm font-bold text-blue-600 bg-blue-100 rounded-full border border-blue-200">
                                {{ $booking->status }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg flex gap-4 items-center border border-gray-200 mb-6">
                        <img src="{{ asset('images/' . $booking->vehicle->vehicle_image) }}" class="w-32 h-20 object-cover rounded-md bg-white border">
                        <div>
                            <h4 class="font-bold text-gray-800 text-lg">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</h4>
                            <p class="text-sm text-gray-600 font-mono bg-gray-200 px-2 py-0.5 rounded inline-block">{{ $booking->vehicle->plate_number }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="border p-4 rounded-lg bg-gray-50">
                            <p class="text-xs text-gray-400 font-bold uppercase mb-1">Pick-up Date & Time</p>
                            <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, h:i A') }}</p>
                            <p class="text-sm text-gray-500 mt-1"><i class="ri-map-pin-line"></i> {{ $booking->pickup_location }}</p>
                        </div>
                        <div class="border p-4 rounded-lg bg-gray-50">
                            <p class="text-xs text-gray-400 font-bold uppercase mb-1">Return Date & Time</p>
                            <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, h:i A') }}</p>
                            <p class="text-sm text-gray-500 mt-1"><i class="ri-map-pin-line"></i> {{ $booking->dropoff_location }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm flex flex-col h-full border-t-4 border-[#cb5c55]">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Payment Receipt</h3>

                    <div class="flex-grow flex items-center justify-center bg-gray-100 rounded-lg mb-4 overflow-hidden border border-gray-300 relative group cursor-pointer" 
                         onclick="window.open(this.querySelector('img').src)"
                         title="Click to view full image">
                        @if($payment && $payment->receipt_image)
                            <img src="{{ asset('storage/' . $payment->receipt_image) }}" alt="Receipt" class="object-contain max-h-80 w-full group-hover:scale-105 transition duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-center justify-center">
                                <span class="text-white opacity-0 group-hover:opacity-100 font-bold bg-black/50 px-3 py-1 rounded">
                                    <i class="ri-zoom-in-line"></i> View
                                </span>
                            </div>
                        @else
                            <div class="text-center py-10 text-gray-400">
                                <i class="ri-image-line text-4xl mb-2"></i>
                                <p>No receipt uploaded yet</p>
                            </div>
                        @endif
                    </div>
                    
                    @if($payment)
                        <div class="text-center mb-6">
                            <p class="text-sm text-gray-500 uppercase font-bold">Total Amount Paid</p>
                            <p class="text-3xl font-bold theme-text">RM {{ number_format($payment->amount, 2) }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mt-auto">
                            <form action="{{ route('admin.payment.reject', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to REJECT this booking?');">
                                @csrf
                                <button type="submit" class="w-full py-3 bg-white text-red-600 font-bold rounded-lg hover:bg-red-50 transition border border-red-200 shadow-sm flex items-center justify-center gap-2">
                                    <i class="ri-close-circle-line text-xl"></i> Reject
                                </button>
                            </form>

                            <form action="{{ route('admin.payment.approve', $booking->id) }}" method="POST" onsubmit="return confirm('Confirm payment and APPROVE booking?');">
                                @csrf
                                <button type="submit" class="w-full py-3 theme-bg text-white font-bold rounded-lg hover:opacity-90 transition shadow-lg flex items-center justify-center gap-2">
                                    <i class="ri-checkbox-circle-line text-xl"></i> Approve
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center text-amber-600 font-medium bg-amber-50 p-4 rounded border border-amber-200">
                            <i class="ri-loader-4-line animate-spin mr-1"></i> Waiting for user upload...
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

</body>
</html>