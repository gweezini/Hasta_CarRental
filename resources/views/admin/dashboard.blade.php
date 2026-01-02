<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Hasta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");
        body { font-family: 'Poppins', sans-serif; }
        .theme-text { color: #cb5c55; }
        .theme-bg { background-color: #cb5c55; }
        
        .nav-active { background-color: rgba(255,255,255,0.2); box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06); }
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
                    <h2 class="text-3xl font-bold text-gray-800">Admin Dashboard</h2>
                    <p class="text-gray-500 text-sm">Welcome back, {{ Auth::user()->name }}</p>
                </div>
                
                <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-green-500 font-bold uppercase tracking-wider bg-green-50 px-2 py-0.5 rounded-full inline-block">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-[#cb5c55] rounded-full flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>

            @if(isset($roadTaxAlerts) && $roadTaxAlerts->count() > 0)
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="ri-alarm-warning-fill text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">Warning: Vehicle Road Tax Expiring Soon</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($roadTaxAlerts as $car)
                                    <li>
                                        <span class="font-bold">{{ $car->plate_number }}</span> ({{ $car->brand }}) 
                                        - Expires: {{ \Carbon\Carbon::parse($car->road_tax_expiry)->format('d M Y') }}
                                        <span class="text-xs bg-white text-red-800 px-2 py-0.5 rounded-full ml-2 border border-red-200 font-bold">
                                            {{ \Carbon\Carbon::parse($car->road_tax_expiry)->diffForHumans() }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($insuranceAlerts) && $insuranceAlerts->count() > 0)
            <div class="mb-8 bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="ri-shield-alert-fill text-orange-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-orange-800">Warning: Vehicle Insurance Expiring Soon</h3>
                        <div class="mt-2 text-sm text-orange-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($insuranceAlerts as $car)
                                    <li>
                                        <span class="font-bold">{{ $car->plate_number }}</span> ({{ $car->brand }}) 
                                        - Expires: {{ \Carbon\Carbon::parse($car->insurance_expiry)->format('d M Y') }}
                                        <span class="text-xs bg-white text-orange-800 px-2 py-0.5 rounded-full ml-2 border border-orange-200 font-bold">
                                            {{ \Carbon\Carbon::parse($car->insurance_expiry)->diffForHumans() }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-400 flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Pending</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $pendingCount ?? 0 }}</h3>
                        <p class="text-[10px] text-yellow-600 mt-1">Verifications Needed</p>
                    </div>
                    <div class="p-3 bg-yellow-50 text-yellow-500 rounded-lg"><i class="ri-time-line text-2xl"></i></div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Fleet</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalCars ?? 0 }}</h3>
                        <p class="text-[10px] text-blue-600 mt-1">Vehicles in system</p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-500 rounded-lg"><i class="ri-roadster-line text-2xl"></i></div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-500 flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Customers</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalCustomers ?? 0 }}</h3>
                        <p class="text-[10px] text-purple-600 mt-1">Registered users</p>
                    </div>
                    <div class="p-3 bg-purple-50 text-purple-500 rounded-lg"><i class="ri-group-line text-2xl"></i></div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#cb5c55] flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        @if(Auth::user()->isTopManagement())
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Revenue</p>
                            <h3 class="text-2xl font-bold text-gray-800">RM {{ number_format($totalRevenue ?? 0, 2) }}</h3>
                            <p class="text-[10px] theme-text mt-1">Lifetime Earnings</p>
                        @else
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Today's Revenue</p>
                            <h3 class="text-2xl font-bold text-gray-800">RM {{ number_format($todayRevenue ?? 0, 2) }}</h3>
                            <p class="text-[10px] theme-text mt-1">Daily Earnings</p>
                        @endif
                    </div>
                    <div class="p-3 bg-red-50 theme-text rounded-lg"><i class="ri-money-dollar-circle-line text-2xl"></i></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center text-sm uppercase tracking-wide">
                        <i class="ri-pie-chart-line mr-2 text-[#cb5c55]"></i> Student by Faculty
                    </h3>
                    <div class="h-64">
                        <canvas id="facultyChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center text-sm uppercase tracking-wide">
                        <i class="ri-building-4-line mr-2 text-blue-500"></i> Student by College
                    </h3>
                    <div class="h-64">
                        <canvas id="collegeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Recent Bookings</h3>
                        <p class="text-xs text-gray-400">Latest transactions</p>
                    </div>
                    <a href="{{ route('admin.bookings.index') }}" class="text-sm theme-text font-medium hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold tracking-wider">
                                <th class="p-4 border-b">Customer</th>
                                <th class="p-4 border-b">Vehicle</th>
                                <th class="p-4 border-b">Dates</th>
                                <th class="p-4 border-b">Status</th>
                                <th class="p-4 border-b text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($bookings as $booking)
                            <tr class="hover:bg-red-50 transition">
                                <td class="p-4">
                                    <p class="font-bold text-gray-800">{{ $booking->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $booking->user->phone_number }}</p>
                                </td>
                                
                                <td class="p-4 text-gray-600">
                                    {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}
                                    <span class="block text-xs text-gray-400">{{ $booking->vehicle->plate_number }}</span>
                                </td>

                                <td class="p-4 text-gray-500 text-xs">
                                    <div><i class="ri-arrow-right-s-line text-green-500"></i> {{ \Carbon\Carbon::parse($booking->start_time)->format('d M') }}</div>
                                    <div><i class="ri-arrow-left-s-line text-red-500"></i> {{ \Carbon\Carbon::parse($booking->end_time)->format('d M') }}</div>
                                </td>

                                <td class="p-4">
                                    @if($booking->status === 'Waiting for Verification')
                                        <span class="px-2 py-1 text-xs font-bold text-blue-700 bg-blue-100 rounded-full border border-blue-200 animate-pulse">Verify Receipt</span>
                                    @elseif($booking->status === 'Approved')
                                        <span class="px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full border border-green-200">Approved</span>
                                    @elseif($booking->status === 'Rejected')
                                        <span class="px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full border border-red-200">Rejected</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-bold text-gray-600 bg-gray-100 rounded-full">{{ $booking->status }}</span>
                                    @endif
                                </td>

                                <td class="p-4 text-center">
                                    @if($booking->status === 'Waiting for Verification')
                                        <a href="{{ route('admin.payment.verify', $booking->id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-xs underline">Verify</a>
                                    @else
                                        <span class="text-gray-300 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">
                                    <i class="ri-inbox-line text-3xl mb-2 block opacity-50"></i> No bookings found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script>
        const facultyLabels = {!! json_encode($facultyLabels ?? []) !!};
        const facultyCounts = {!! json_encode($facultyCounts ?? []) !!};
        const collegeLabels = {!! json_encode($collegeLabels ?? []) !!};
        const collegeCounts = {!! json_encode($collegeCounts ?? []) !!};

        const ctxFaculty = document.getElementById('facultyChart').getContext('2d');
        new Chart(ctxFaculty, {
            type: 'bar',
            data: {
                labels: facultyLabels,
                datasets: [{
                    label: 'Students',
                    data: facultyCounts,
                    backgroundColor: 'rgba(203, 92, 85, 0.8)', // #cb5c55
                    borderRadius: 4,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });

        const ctxCollege = document.getElementById('collegeChart').getContext('2d');
        new Chart(ctxCollege, {
            type: 'doughnut',
            data: {
                labels: collegeLabels,
                datasets: [{
                    data: collegeCounts,
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6366f1'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    </script>
</body>
</html>