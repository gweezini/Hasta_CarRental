<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <title>Add Vehicle - Hasta Admin</title>
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
                <a href="{{ route('admin.vehicle.index') }}" class="hover:underline hover:text-[#cb5c55]">
                    <i class="ri-arrow-left-line"></i> Back to Fleet
                </a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Add New Vehicle</span>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                    <p class="font-bold flex items-center"><i class="ri-alert-line mr-2"></i> Please fix the following errors:</p>
                    <ul class="list-disc pl-5 mt-2 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800">Add New Vehicle</h2>
                    <p class="text-sm text-gray-500 mt-1">Fill in the details to register a new car into the system.</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('admin.vehicle.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-8 p-6 border-2 border-dashed border-gray-300 rounded-lg hover:bg-gray-50 transition text-center">
                            <label class="cursor-pointer block">
                                <span class="block text-gray-500 font-medium mb-2">Vehicle Image</span>
                                <i class="ri-image-add-line text-4xl text-gray-400 mb-2"></i>
                                <span class="block text-xs text-gray-400">Click to upload (JPG, PNG)</span>
                                <input type="file" name="vehicle_image" accept="image/*" class="hidden" onchange="previewImage(event)">
                            </label>
                            <img id="image-preview" class="mt-4 mx-auto max-h-48 rounded-lg shadow-sm hidden">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="space-y-4">
                                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Car Details</h3>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                                    <input type="text" name="brand" value="{{ old('brand') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500" placeholder="e.g. Perodua">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                                    <input type="text" name="model" value="{{ old('model') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500" placeholder="e.g. Myvi">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                                        <input type="number" name="year" value="{{ old('year') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                                        <input type="number" name="capacity" value="{{ old('capacity') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                    <select name="type_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                        <option value="">-- Select Type --</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->code }}" {{ old('type_id') == $type->code ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Identification & Status</h3>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number</label>
                                        <input type="text" name="plate_number" value="{{ old('plate_number') }}" class="w-full border-gray-300 rounded-md shadow-sm uppercase font-mono" placeholder="ABC 1234">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Custom ID</label>
                                        <input type="text" name="vehicle_id_custom" value="{{ old('vehicle_id_custom') }}" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="V001">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ownership</label>
                                    <select name="is_hasta_owned" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                        <option value="1" {{ old('is_hasta_owned') == '1' ? 'selected' : '' }}>Yes (Hasta Owned)</option>
                                        <option value="0" {{ old('is_hasta_owned') == '0' ? 'selected' : '' }}>No (Leased/External)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Per Hour (RM)</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">RM</span>
                                        </div>
                                        <input type="number" step="0.01" name="price_per_hour" value="{{ old('price_per_hour') }}" class="block w-full rounded-md border-gray-300 pl-10 focus:border-orange-500 focus:ring-orange-500">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Initial Status</label>
                                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="Available">Available</option>
                                        <option value="Unavailable">Unavailable</option>
                                        <option value="Maintenance">Maintenance</option>
                                    </select>
                                </div>
                            </div>

                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Road Tax Expiry</label>
                                    <input type="date" name="road_tax_expiry" value="{{ old('road_tax_expiry') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Insurance Expiry</label>
                                    <input type="date" name="insurance_expiry" value="{{ old('insurance_expiry') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Fuel</label>
                                    <div class="flex items-center gap-2">
                                        <input type="range" name="current_fuel_bars" min="0" max="10" value="{{ old('current_fuel_bars', 10) }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="this.nextElementSibling.value = this.value">
                                        <output class="text-sm font-bold text-gray-700 w-8">10</output> <span class="text-xs text-gray-500">Bars</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('admin.vehicle.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2.5 theme-bg text-white font-bold rounded-lg hover:opacity-90 shadow-md transition transform hover:-translate-y-0.5">
                                <i class="ri-save-line mr-1"></i> Save Vehicle
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('image-preview');
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>
</html>