<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <title>Customer Details - Hasta Admin</title>
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
                    <button class="w-full py-2 bg-white text-[#cb5c55] rounded hover:bg-gray-100 transition font-bold shadow-md">
                        <i class="ri-logout-box-line mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-8 ml-64">
            <div class="mb-6 flex items-center gap-2 text-gray-500 text-sm">
                <a href="{{ route('admin.customers.index') }}" class="hover:underline hover:text-[#cb5c55]">
                    <i class="ri-arrow-left-line"></i> Back to Customer List
                </a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Customer Details</span>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-100 p-8 flex justify-between items-start">
                    <div class="flex gap-6">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=cb5c55&color=fff&size=128" 
                             alt="{{ $customer->name }}"
                             class="w-24 h-24 rounded-full border-4 border-white shadow-md">
                        
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">{{ $customer->name }}</h1>
                            <div class="flex items-center gap-4 mt-2 text-gray-500 text-sm">
                                <span><i class="ri-mail-line mr-1"></i> {{ $customer->email }}</span>
                                <span><i class="ri-calendar-line mr-1"></i> Joined {{ $customer->created_at->format('M Y') }}</span>
                            </div>
                            <div class="mt-3">
                                @if($customer->is_blacklisted)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        <i class="ri-prohibited-line mr-1"></i> BLACKLISTED
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <i class="ri-check-double-line mr-1"></i> Good Standing
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">Personal Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 gap-x-12">
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Matric / Staff ID</span>
                            <span class="text-base font-medium text-gray-800">{{ $customer->matric_staff_id }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">NRIC / Passport</span>
                            <span class="text-base font-medium text-gray-800">{{ $customer->nric_passport }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Phone Number</span>
                            <span class="text-base font-medium text-gray-800">{{ $customer->phone_number }}</span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">College</span>
                            <span class="text-base font-medium text-gray-800">{{ $customer->college ? $customer->college->name : '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Faculty</span>
                            <span class="text-base font-medium text-gray-800">{{ $customer->faculty ? $customer->faculty->name : '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Driving License</span>
                            <span class="text-base font-medium text-gray-800">{{ $customer->driving_license }}</span>
                        </div>

                        <div class="col-span-1 md:col-span-3">
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Home Address</span>
                            <span class="text-base font-medium text-gray-800">{{ $customer->address }}</span>
                        </div>
                    </div>

                    <div class="mt-10 pt-8 border-t border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Uploaded Documents</h3>
                        <div class="flex gap-4">
                            @if($customer->driving_license_path)
                                <a href="{{ asset('storage/' . $customer->driving_license_path) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition border border-blue-200 font-medium text-sm">
                                    <i class="ri-file-user-line"></i> View Driving License
                                </a>
                            @else
                                <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg border border-gray-200 text-sm cursor-not-allowed">
                                    No License Uploaded
                                </span>
                            @endif

                            @if($customer->matric_card_path)
                                <a href="{{ asset('storage/' . $customer->matric_card_path) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition border border-purple-200 font-medium text-sm">
                                    <i class="ri-id-card-line"></i> View Matric Card
                                </a>
                            @else
                                <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg border border-gray-200 text-sm cursor-not-allowed">
                                    No Matric Card Uploaded
                                </span>
                            @endif

                            @if($customer->nric_passport_path)
                                <a href="{{ asset('storage/' . $customer->nric_passport_path) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition border border-green-200 font-medium text-sm">
                                    <i class="ri-id-card-line"></i> View NRIC/Passport
                                </a>
                            @else
                                <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg border border-gray-200 text-sm cursor-not-allowed">
                                    No NRIC/Passport Uploaded
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </main>
    </div>

</body>
</html>