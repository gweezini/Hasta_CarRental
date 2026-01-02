<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hasta Car Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
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

            <a href="{{ route('admin.reports') }}" class="flex items-center px-6 py-3 text-sm font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.reports') ? 'sidebar-active' : '' }}">
                <i class="ri-file-chart-line mr-3 text-lg"></i> Reports
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-white text-[#cb5c55] rounded-lg font-bold hover:bg-gray-100 transition shadow-sm">
                    <i class="ri-logout-box-r-line mr-2"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 z-10">
            <h2 class="text-2xl font-bold text-gray-800">Admin Dashboard</h2>
            
            <div class="flex items-center gap-6">
                
                <div x-data="{ open: false }" class="relative">
                    @php
                        // 防止变量未定义报错
                        $roadTaxAlerts = $roadTaxAlerts ?? collect();
                        $insuranceAlerts = $insuranceAlerts ?? collect();
                        $alertCount = $roadTaxAlerts->count() + $insuranceAlerts->count();
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

                    <div x-show="open" style="display: none;" 
                         x-transition.origin.top.right
                         class="absolute right-0 mt-3 w-96 bg-white rounded-xl shadow-2xl overflow-hidden z-50 border border-gray-100">
                        
                        <div class="px-5 py-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                            <h3 class="font-bold text-gray-700">Expiry Alerts</h3>
                            @if($alertCount > 0)
                                <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full">{{ $alertCount }} Issues</span>
                            @endif
                        </div>

                        <div class="max-h-80 overflow-y-auto">
                            @if($alertCount == 0)
                                <div class="p-8 text-center text-gray-400">
                                    <i class="ri-checkbox-circle-line text-4xl mb-2 text-green-400 block"></i>
                                    <p class="text-sm">All vehicles are up to date!</p>
                                </div>
                            @else
                                @foreach($roadTaxAlerts as $car)
                                    <div class="px-5 py-4 border-b border-gray-50 hover:bg-red-50/30 transition flex items-start gap-3">
                                        <div class="mt-1 w-8 h-8 rounded-full bg-red-100 text-red-500 flex items-center justify-center flex-shrink-0">
                                            <i class="ri-file-warning-line"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">Road Tax Expiring</p>
                                            <p class="text-xs text-gray-600 mt-0.5">
                                                <span class="font-mono font-bold">{{ $car->plate_number }}</span> ({{ $car->brand }} {{ $car->model }})
                                            </p>
                                            <p class="text-xs text-red-500 font-medium mt-1">
                                                Expires: {{ \Carbon\Carbon::parse($car->road_tax_expiry)->format('d M Y') }} 
                                                ({{ \Carbon\Carbon::parse($car->road_tax_expiry)->diffForHumans() }})
                                            </p>
                                        </div>
                                    </div>
                                @endforeach

                                @foreach($insuranceAlerts as $car)
                                    <div class="px-5 py-4 border-b border-gray-50 hover:bg-orange-50/30 transition flex items-start gap-3">
                                        <div class="mt-1 w-8 h-8 rounded-full bg-orange-100 text-orange-500 flex items-center justify-center flex-shrink-0">
                                            <i class="ri-shield-alert-line"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">Insurance Expiring</p>
                                            <p class="text-xs text-gray-600 mt-0.5">
                                                <span class="font-mono font-bold">{{ $car->plate_number }}</span> ({{ $car->brand }} {{ $car->model }})
                                            </p>
                                            <p class="text-xs text-orange-500 font-medium mt-1">
                                                Expires: {{ \Carbon\Carbon::parse($car->insurance_expiry)->format('d M Y') }} 
                                                ({{ \Carbon\Carbon::parse($car->insurance_expiry)->diffForHumans() }})
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <a href="{{ route('admin.vehicle.index') }}" class="block text-center py-3 text-xs font-bold text-[#cd5c5c] hover:bg-gray-50 transition border-t border-gray-100">
                            Manage Fleet &rarr;
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-gray-50 px-4 py-2 rounded-full border border-gray-200">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-green-500 font-bold uppercase tracking-wide">● Online</p>
                    </div>
                    <div class="h-9 w-9 rounded-full bg-[#cd5c5c] text-white flex items-center justify-center font-bold text-sm shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>

            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-[#f3f4f6]">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-yellow-400 flex justify-between items-center hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending</p>
                        <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $pendingCount }}</p>
                        <p class="text-xs text-yellow-600 mt-1">Verifications needed</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg text-yellow-500 text-2xl">
                        <i class="ri-time-line"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500 flex justify-between items-center hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Fleet</p>
                        <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalCars }}</p>
                        <p class="text-xs text-blue-600 mt-1">Vehicles in system</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg text-blue-500 text-2xl">
                        <i class="ri-roadster-line"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-purple-500 flex justify-between items-center hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Customers</p>
                        <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalCustomers }}</p>
                        <p class="text-xs text-purple-600 mt-1">Registered users</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg text-purple-500 text-2xl">
                        <i class="ri-group-line"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-[#cd5c5c] flex justify-between items-center hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Today's Revenue</p>
                        <p class="text-3xl font-extrabold text-gray-800 mt-1">RM {{ number_format($todayRevenue, 2) }}</p>
                        <p class="text-xs text-green-600 mt-1">Total: RM {{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg text-[#cd5c5c] text-2xl">
                        <i class="ri-money-dollar-circle-line"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <i class="ri-pie-chart-line mr-2 text-[#cd5c5c]"></i> Student by Faculty
                    </h3>
                    <div class="h-64">
                        <canvas id="facultyChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <i class="ri-building-4-line mr-2 text-blue-500"></i> Student by College
                    </h3>
                    <div class="h-64 flex justify-center">
                        <canvas id="collegeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Recent Bookings</h3>
                    
                    <a href="{{ route('admin.bookings.index') }}" class="text-sm font-bold text-[#cd5c5c] hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-bold">
                            <tr>
                                <th class="px-6 py-4">Booking ID</th>
                                <th class="px-6 py-4">Customer</th>
                                <th class="px-6 py-4">Vehicle</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-800">#{{ $booking->id }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $booking->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $booking->user->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-mono font-bold">{{ $booking->vehicle->plate_number }}</span>
                                    <span class="ml-1 text-xs">{{ $booking->vehicle->model }}</span>
                                </td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match($booking->status) {
                                            'Approved' => 'bg-green-100 text-green-700',
                                            'Rejected' => 'bg-red-100 text-red-700',
                                            'Waiting for Verification' => 'bg-yellow-100 text-yellow-700',
                                            'Completed' => 'bg-gray-100 text-gray-700',
                                            default => 'bg-gray-100 text-gray-600'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-800">
                                    RM {{ number_format($booking->total_rental_fee, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 text-center text-xs text-gray-400">
                &copy; 2026 Hasta Car Rental Admin Panel
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Faculty Chart
        const facultyCtx = document.getElementById('facultyChart').getContext('2d');
        new Chart(facultyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($facultyLabels) !!},
                datasets: [{
                    label: 'Students',
                    data: {!! json_encode($facultyCounts) !!},
                    backgroundColor: '#cd5c5c',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } },
                plugins: { legend: { display: false } }
            }
        });

        // College Chart
        const collegeCtx = document.getElementById('collegeChart').getContext('2d');
        new Chart(collegeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($collegeLabels) !!},
                datasets: [{
                    data: {!! json_encode($collegeCounts) !!},
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right', labels: { usePointStyle: true, font: { size: 11 } } } },
                cutout: '70%'
            }
        });
    </script>
</body>
</html>