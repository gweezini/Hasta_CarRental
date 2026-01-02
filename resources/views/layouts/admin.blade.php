<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <title>Hasta Admin</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen relative">
        
        <aside class="w-64 bg-[#cb5c55] text-white flex flex-col fixed top-0 left-0 h-full shadow-lg z-50 overflow-y-auto">
            <div class="p-6 text-center border-b border-white/20">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta Logo" class="w-32 mx-auto rounded-lg shadow-sm">
                </a>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block py-3 px-4 rounded text-white transition hover:bg-white/10">
                    <i class="ri-dashboard-line mr-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="block py-3 px-4 rounded text-white transition hover:bg-white/10">
                    <i class="ri-list-check-2 mr-2"></i> Bookings
                </a>
                <a href="{{ route('admin.vehicle.index') }}" class="block py-3 px-4 rounded text-white transition hover:bg-white/10">
                    <i class="ri-car-line mr-2"></i> Fleet Management
                </a>
                <a href="{{ route('admin.customers.index') }}" class="block py-3 px-4 rounded text-white transition hover:bg-white/10">
                    <i class="ri-group-line mr-2"></i> Customers
                </a>
                <a href="{{ route('admin.vouchers.index') }}" class="block py-3 px-4 rounded text-white transition hover:bg-white/10">
                    <i class="ri-coupon-3-line mr-2"></i> Vouchers
                </a>

                @if(Auth::user()->isTopManagement())
                <a href="{{ route('admin.reports') }}" class="block py-3 px-4 rounded text-white transition hover:bg-white/10">
                      <i class="ri-file-chart-line mr-2"></i> Reports
                </a>
                @endif
            </nav>

            <div class="p-4 border-t border-white/20 mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full py-2 bg-white text-[#cb5c55] rounded hover:bg-gray-100 transition font-bold shadow-md">
                        <i class="ri-logout-box-line mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="ml-64 p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>