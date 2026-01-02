<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <title>Edit Vehicle - Hasta Admin</title>
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
            <div class="mb-6 flex items-center gap-2 text-gray-500 text-sm">
                <a href="{{ route('admin.vehicle.index') }}" class="hover:underline hover:text-[#cb5c55]">
                    <i class="ri-arrow-left-line"></i> Back to Fleet
                </a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Edit Vehicle</span>
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
                <div class="bg-gray-50 border-b border-gray-100 p-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Edit Vehicle: {{ $vehicle->plate_number }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Update vehicle details and status.</p>
                    </div>
                    <span class="px-3 py-1 rounded text-xs font-bold bg-gray-200 text-gray-600">ID: {{ $vehicle->vehicle_id_custom }}</span>
                </div>

                <div class="p-8">
                    <form action="{{ route('admin.vehicle.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-8 flex gap-6 items-start border-b border-gray-100 pb-8">
                            <div class="w-1/3">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Current Image</label>
                                <div class="relative group">
                                    <img id="image-preview" 
                                         src="{{ $vehicle->vehicle_image ? asset('images/' . $vehicle->vehicle_image) : 'https://via.placeholder.com/400x250?text=No+Image' }}" 
                                         class="w-full h-48 object-cover rounded-lg border shadow-sm bg-gray-50">
                                </div>
                            </div>
                            <div class="w-2/3">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Upload New Image (Optional)</label>
                                <div class="p-6 border-2 border-dashed border-gray-300 rounded-lg hover:bg-gray-50 transition text-center cursor-pointer relative">
                                    <input type="file" name="vehicle_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event)">
                                    <i class="ri-upload-cloud-2-line text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500">Click to replace image</p>
                                    <p class="text-xs text-gray-400 mt-1">Leave empty to keep current image</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="space-y-4">
                                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Car Details</h3>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                                    <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                                    <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                                        <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                                        <input type="number" name="capacity" value="{{ old('capacity', $vehicle->capacity) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                    <select name="type_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                        @foreach($types as $type)
                                            <option value="{{ $type->code }}" {{ old('type_id', $vehicle->type_id) == $type->code ? 'selected' : '' }}>
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
                                        <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" class="w-full border-gray-300 rounded-md shadow-sm uppercase font-mono">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Custom ID</label>
                                        <input type="text" name="vehicle_id_custom" value="{{ old('vehicle_id_custom', $vehicle->vehicle_id_custom) }}" class="w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ownership</label>
                                    <select name="is_hasta_owned" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                        <option value="1" {{ old('is_hasta_owned', $vehicle->is_hasta_owned) == '1' ? 'selected' : '' }}>Yes (Hasta Owned)</option>
                                        <option value="0" {{ old('is_hasta_owned', $vehicle->is_hasta_owned) == '0' ? 'selected' : '' }}>No (Leased/External)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Per Hour (RM)</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">RM</span>
                                        </div>
                                        <input type="number" step="0.01" name="price_per_hour" value="{{ old('price_per_hour', $vehicle->price_per_hour) }}" class="block w-full rounded-md border-gray-300 pl-10 focus:border-orange-500 focus:ring-orange-500">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm font-bold text-gray-700">
                                        <option value="Available" {{ old('status', $vehicle->status) == 'Available' ? 'selected' : '' }}>Available</option>
                                        <option value="Unavailable" {{ old('status', $vehicle->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                        <option value="Maintenance" {{ old('status', $vehicle->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                </div>
                            </div>

                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Road Tax Expiry</label>
                                    <input type="date" name="road_tax_expiry" value="{{ old('road_tax_expiry', $vehicle->road_tax_expiry) }}" class="w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Insurance Expiry</label>
                                    <input type="date" name="insurance_expiry" value="{{ old('insurance_expiry', $vehicle->insurance_expiry) }}" class="w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Fuel</label>
                                    <div class="flex items-center gap-2">
                                        <input type="range" name="current_fuel_bars" min="0" max="10" value="{{ old('current_fuel_bars', $vehicle->current_fuel_bars) }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer theme-text" oninput="this.nextElementSibling.value = this.value">
                                        <output class="text-sm font-bold text-gray-700 w-8">{{ $vehicle->current_fuel_bars }}</output> <span class="text-xs text-gray-500">Bars</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('admin.vehicle.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2.5 theme-bg text-white font-bold rounded-lg hover:opacity-90 shadow-md transition transform hover:-translate-y-0.5">
                                <i class="ri-save-line mr-1"></i> Update Changes
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
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>
</html>