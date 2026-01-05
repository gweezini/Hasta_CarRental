@extends('layouts.admin')

@section('header_title')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.vehicle.index') }}" class="text-gray-400 hover:text-[#cb5c55] transition" title="Back to Fleet">
            <i class="ri-arrow-left-line text-xl align-middle"></i>
        </a>
        <span class="align-middle">Edit Vehicle: <span class="text-[#cb5c55]">{{ $vehicle->plate_number }}</span></span>
    </div>
@endsection

@section('content')

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
                <h2 class="text-xl font-bold text-gray-800">Vehicle Details</h2>
                <p class="text-sm text-gray-500 mt-1">Update vehicle details and status.</p>
            </div>
            <span class="px-3 py-1 rounded text-xs font-bold bg-gray-200 text-gray-600">ID: {{ $vehicle->vehicle_id_custom }}</span>
        </div>

        <div class="p-8">
            <form action="{{ route('admin.vehicle.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
    
                <div class="mb-8 flex flex-col md:flex-row gap-6 items-start border-b border-gray-100 pb-8">
                    <div class="w-full md:w-1/3">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Current Image</label>
                        <div class="relative group">
                            <img id="image-preview" 
                                 src="{{ $vehicle->vehicle_image ? asset('images/' . $vehicle->vehicle_image) : 'https://via.placeholder.com/400x250?text=No+Image' }}" 
                                 class="w-full h-48 object-cover rounded-lg border shadow-sm bg-gray-50">
                        </div>
                    </div>
                    <div class="w-full md:w-2/3">
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
                            <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55]">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                            <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55]">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                                <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                                <input type="number" name="capacity" value="{{ old('capacity', $vehicle->capacity) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55]">
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
                                <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" class="w-full border-gray-300 rounded-md shadow-sm uppercase font-mono focus:border-[#cb5c55] focus:ring-[#cb5c55]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Custom ID</label>
                                <input type="text" name="vehicle_id_custom" value="{{ old('vehicle_id_custom', $vehicle->vehicle_id_custom) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ownership</label>
                            <select name="is_hasta_owned" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55]">
                                <option value="1" {{ old('is_hasta_owned', $vehicle->is_hasta_owned) == '1' ? 'selected' : '' }}>Yes (Hasta Owned)</option>
                                <option value="0" {{ old('is_hasta_owned', $vehicle->is_hasta_owned) == '0' ? 'selected' : '' }}>No (Leased/External)</option>
                            </select>
                        </div>



                        @if($vehicle->pricingTier)
                            <div class="col-span-1 md:col-span-2 bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <h3 class="text-sm font-bold text-blue-800 mb-3 flex items-center">
                                    <i class="ri-price-tag-3-line mr-2"></i> 
                                    Hourly Rates for Tier: <span class="underline ml-1">{{ $vehicle->pricingTier->name }}</span>
                                </h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach([1, 3, 5, 7, 9, 12, 24] as $hour)
                                        @php
                                            $rate = $vehicle->pricingTier->rules->where('hour_limit', $hour)->first();
                                            $price = $rate ? $rate->price : '';
                                        @endphp
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 mb-1">{{ $hour }} Hour(s)</label>
                                            <div class="relative rounded-md shadow-sm">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2">
                                                    <span class="text-gray-500 text-xs">RM</span>
                                                </div>
                                                <input type="number" step="0.01" name="rates[{{ $hour }}]" value="{{ $price }}" class="block w-full rounded text-sm border-gray-300 pl-8 focus:border-blue-500 focus:ring-blue-500" placeholder="0.00">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-blue-600 mt-2 italic">* Updating these rates will affect all vehicles in this tier.</p>
                            </div>
                        @else
                            <div class="col-span-1 md:col-span-2 bg-yellow-50 p-4 rounded-lg border border-yellow-100 text-center">
                                <p class="text-yellow-800 font-bold mb-2">No Custom Tier Assigned</p>
                                <p class="text-sm text-yellow-700">Please assign a tier first to enable advanced hourly pricing.</p>
                                <a href="{{ route('admin.pricing.index') }}" class="inline-block mt-2 text-sm font-bold text-blue-600 hover:underline">Manage Tiers</a>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-md shadow-sm font-bold text-gray-700 focus:border-[#cb5c55] focus:ring-[#cb5c55]">
                                <option value="Available" {{ old('status', $vehicle->status) == 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="Unavailable" {{ old('status', $vehicle->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                <option value="Maintenance" {{ old('status', $vehicle->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Road Tax Expiry</label>
                            <input type="date" name="road_tax_expiry" value="{{ old('road_tax_expiry', $vehicle->road_tax_expiry) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Insurance Expiry</label>
                            <input type="date" name="insurance_expiry" value="{{ old('insurance_expiry', $vehicle->insurance_expiry) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Fuel</label>
                            <div class="flex items-center gap-2">
                                <input type="range" name="current_fuel_bars" min="0" max="10" value="{{ old('current_fuel_bars', $vehicle->current_fuel_bars) }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#cb5c55]" oninput="this.nextElementSibling.value = this.value">
                                <output class="text-sm font-bold text-gray-700 w-8">{{ $vehicle->current_fuel_bars }}</output> <span class="text-xs text-gray-500">Bars</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('admin.vehicle.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#cb5c55] text-white font-bold rounded-lg hover:opacity-90 shadow-md transition transform hover:-translate-y-0.5">
                        <i class="ri-save-line mr-1"></i> Update Changes
                    </button>
                </div>
            </form>
        </div>
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

        function updateManageRatesLink() {
            const select = document.getElementById('pricing-tier-select');
            const link = document.getElementById('manage-rates-link');
            const tierId = select.value;
            
            if (tierId) {
                // Construct URL: /admin/pricing/{id}/edit using JS string replacement or base URL
                const baseUrl = "{{ route('admin.pricing.edit', ':id') }}";
                link.href = baseUrl.replace(':id', tierId);
                link.classList.remove('hidden');
            } else {
                link.classList.add('hidden');
            }
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', updateManageRatesLink);
    </script>
@endsection
