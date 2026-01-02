<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hasta Car Rental</title>
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
            <h2 class="text-2xl font-bold text-gray-800">Admin Dashboard</h2>
            
            <div class="flex items-center gap-4">
                
                <div x-data="{ open: false }" class="relative">
                    @php
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
                    
                    <div x-show="open" style="display: none;" x-transition class="absolute right-0 mt-3 w-96 bg-white rounded-xl shadow-2xl border z-50 overflow-hidden text-left">
                        <div class="px-5 py-4 border-b bg-gray-50/50 font-bold text-base uppercase text-gray-700">Expiry Alerts</div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse($roadTaxAlerts as $car)
                                <a href="{{ route('admin.vehicle.edit', $car->id) }}" class="block px-5 py-4 border-b hover:bg-red-50 transition">
                                    <p class="text-sm font-bold text-gray-800 uppercase">Road Tax: {{ $car->plate_number }}</p>
                                    <p class="text-xs text-red-500 mt-1 font-medium">{{ \Carbon\Carbon::parse($car->road_tax_expiry)->diffForHumans() }}</p>
                                </a>
                            @empty @endforelse
                            @forelse($insuranceAlerts as $car)
                                <a href="{{ route('admin.vehicle.edit', $car->id) }}" class="block px-5 py-4 border-b hover:bg-orange-50 transition">
                                    <p class="text-sm font-bold text-gray-800 uppercase">Insurance: {{ $car->plate_number }}</p>
                                    <p class="text-xs text-orange-500 mt-1 font-medium">{{ \Carbon\Carbon::parse($car->insurance_expiry)->diffForHumans() }}</p>
                                </a>
                            @empty @endforelse
                        </div>
                        <a href="{{ route('admin.notifications') }}" class="block text-center py-4 text-sm font-black text-[#cd5c5c] hover:bg-gray-50 border-t tracking-widest uppercase">
                            View All Notifications
                        </a>
                    </div>
                </div>

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
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-yellow-400 flex justify-between items-center hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending</p>
                        <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $pendingCount }}</p>
                        <p class="text-[10px] text-yellow-600 font-bold mt-1">Verifications needed</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg text-yellow-500 text-2xl"><i class="ri-time-line"></i></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500 flex justify-between items-center hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Fleet</p>
                        <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalCars }}</p>
                        <p class="text-[10px] text-blue-600 font-bold mt-1">Vehicles in system</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg text-blue-500 text-2xl"><i class="ri-roadster-line"></i></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-purple-500 flex justify-between items-center hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Customers</p>
                        <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalCustomers }}</p>
                        <p class="text-[10px] text-purple-600 font-bold mt-1">Registered users</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg text-purple-500 text-2xl"><i class="ri-group-line"></i></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-[#cd5c5c] flex justify-between items-center hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Today's Revenue</p>
                        <p class="text-3xl font-extrabold text-gray-800 mt-1">RM {{ number_format($todayRevenue, 2) }}</p>
                        
                        @if(Auth::user()->isTopManagement())
                            <p class="text-[10px] font-bold text-green-600 mt-1 bg-green-50 px-2 py-0.5 rounded w-fit">
                                Total: RM {{ number_format($totalRevenue, 2) }}
                            </p>
                        @endif
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg text-[#cd5c5c] text-2xl"><i class="ri-money-dollar-circle-line"></i></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6"><i class="ri-pie-chart-line mr-2 text-[#cd5c5c]"></i> Student by Faculty</h3>
                    <div class="h-64"><canvas id="facultyChart"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6"><i class="ri-building-4-line mr-2 text-blue-500"></i> Student by College</h3>
                    <div class="h-64 flex justify-center"><canvas id="collegeChart"></canvas></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                    <h3 class="text-lg font-bold text-gray-800 tracking-tight">Recent Bookings</h3>
                    <a href="{{ route('admin.bookings.index') }}" class="text-sm font-bold text-[#cd5c5c] hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto text-left">
                    <table class="w-full text-sm text-gray-600">
                        <thead class="bg-gray-50 text-[10px] uppercase text-gray-400 font-black tracking-widest border-b">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Customer</th>
                                <th class="px-6 py-4">Vehicle</th>
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

                                <td class="px-6 py-4 uppercase tracking-tighter">
                                    @php
                                        $style = match($booking->status) {
                                            'Approved' => 'bg-green-100 text-green-700 border-green-200',
                                            'Rejected' => 'bg-red-100 text-red-700 border-red-200',
                                            'Waiting for Verification', 'Verify Receipt' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'Completed' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            default => 'bg-gray-100 text-gray-400'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold border {{ $style }} uppercase tracking-wider">
                                        {{ $booking->status == 'Waiting for Verification' ? 'Verify Receipt' : $booking->status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($booking->status == 'Waiting for Verification')
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
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 text-center text-xs text-gray-400">&copy; 2026 Hasta Car Rental Admin Panel</div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const barColors = ['#3B82F6', '#10B981', '#F59E0B', '#6366F1', '#EC4899', '#8B5CF6', '#14B8A6', '#F97316', '#06B6D4', '#84CC16', '#D946EF', '#EAB308', '#A855F7', '#22C55E', '#64748B'];
        const pieColors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#F97316', '#6366F1', '#14B8A6', '#84CC16', '#D946EF', '#64748B', '#A855F7', '#EAB308'];

        // Clean Labels logic
        const rawFacultyLabels = {!! json_encode($facultyLabels) !!};
        const cleanFacultyLabels = rawFacultyLabels.map(label => label.replace('Faculty of ', ''));

        new Chart(document.getElementById('facultyChart'), {
            type: 'bar',
            data: { 
                labels: cleanFacultyLabels, 
                datasets: [{ 
                    data: {!! json_encode($facultyCounts) !!}, 
                    backgroundColor: barColors, 
                    borderRadius: 4, 
                    barThickness: 25 
                }] 
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } }, 
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f3f4f6', drawBorder: false }, 
                        ticks: { font: { size: 10, family: 'Poppins' }, color: '#6b7280' }
                    }, 
                    x: { 
                        grid: { display: false },
                        ticks: { 
                            font: { size: 9, family: 'Poppins' }, 
                            color: '#6b7280',
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0
                        } 
                    } 
                } 
            }
        });

        new Chart(document.getElementById('collegeChart'), {
            type: 'doughnut',
            data: { 
                labels: {!! json_encode($collegeLabels) !!}, 
                datasets: [{ 
                    data: {!! json_encode($collegeCounts) !!}, 
                    backgroundColor: pieColors, 
                    borderWidth: 2, 
                    borderColor: '#ffffff',
                    hoverOffset: 5 
                }] 
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { 
                        position: 'right', 
                        labels: { 
                            usePointStyle: true, 
                            boxWidth: 8, 
                            font: { size: 11, family: 'Poppins', weight: '500' },
                            padding: 12, 
                            color: '#4b5563' 
                        } 
                    } 
                }, 
                cutout: '65%' 
            }
        });
    </script>
</body>
</html>