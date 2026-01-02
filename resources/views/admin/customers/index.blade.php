<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <title>Customer List - Hasta Admin</title>
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
                    <h2 class="text-3xl font-bold text-gray-800">Customer List</h2>
                    <p class="text-gray-500 text-sm">View registered students and staff</p>
                </div>
                
                <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-full shadow-sm">
                    <span class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 bg-[#cb5c55] rounded-full flex items-center justify-center text-white font-bold text-xs">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase font-bold tracking-wider">
                                <th class="p-4 border-b">ID</th>
                                <th class="p-4 border-b">Name</th>
                                <th class="p-4 border-b">Matric / Staff ID</th>
                                <th class="p-4 border-b">Contact</th>
                                <th class="p-4 border-b">College</th>
                                <th class="p-4 border-b text-center">Blacklist Status</th>
                                <th class="p-4 border-b text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($customers as $customer)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 text-gray-500">#{{ $customer->id }}</td>
                                <td class="p-4 font-bold text-gray-800">{{ $customer->name }}</td>
                                <td class="p-4 font-mono text-gray-600">{{ $customer->matric_staff_id }}</td>
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-700">{{ $customer->phone_number }}</span>
                                        <span class="text-xs text-gray-400">{{ $customer->email }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-gray-600">{{ $customer->college ? $customer->college->name : '-' }}</td>
                                <td class="p-4 text-center">
                                    @if($customer->is_blacklisted)
                                        <span class="px-3 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full border border-red-200">Blacklisted</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full border border-green-200">Good</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition inline-block" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{-- $customers->links() --}}
            </div>
        </main>
    </div>

</body>
</html>