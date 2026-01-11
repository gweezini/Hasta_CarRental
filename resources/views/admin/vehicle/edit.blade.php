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

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Header --}}
        <div class="bg-white border-b border-gray-100 p-6 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Vehicle Details</h2>
                <p class="text-sm text-gray-500 mt-1">Update vehicle details and status.</p>
            </div>
            <span class="px-3 py-1 rounded text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">ID: {{ $vehicle->vehicle_id_custom }}</span>
        </div>

        <div class="p-8">
            <form action="{{ route('admin.vehicle.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
    
                {{-- 1. Image Section --}}
                <div class="mb-10 flex flex-col md:flex-row gap-8 items-start border-b border-gray-100 pb-10">
                    <div class="w-full md:w-1/3">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Current Image</label>
                        <div class="relative group p-1 border border-gray-200 rounded-xl bg-gray-50">
                            <img id="image-preview" 
                                 src="{{ $vehicle->vehicle_image ? asset('images/' . $vehicle->vehicle_image) : 'https://via.placeholder.com/400x250?text=No+Image' }}" 
                                 class="w-full h-48 object-cover rounded-lg">
                        </div>
                    </div>
                    <div class="w-full md:w-2/3">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Upload New Image (Optional)</label>
                        <div class="p-8 border-2 border-dashed border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition text-center cursor-pointer relative group bg-white">
                            <input type="file" name="vehicle_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event)">
                            <i class="ri-upload-cloud-2-line text-4xl text-gray-400 group-hover:text-[#cb5c55] transition mb-3"></i>
                            <p class="text-base font-medium text-gray-700">Click to replace image</p>
                            <p class="text-sm text-gray-500 mt-1">Leave empty to keep current image</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    
                    {{-- 2. Car Details (Left) --}}
                    <div class="space-y-6">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-2 flex items-center">
                            <i class="ri-car-line mr-2 text-[#cb5c55]"></i> Car Info
                        </h3>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Brand</label>
                            <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 font-medium">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Model</label>
                            <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 font-medium">
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Year</label>
                                <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 font-medium">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Capacity</label>
                                <input type="number" name="capacity" value="{{ old('capacity', $vehicle->capacity) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Vehicle Type</label>
                            <select name="type_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 font-medium bg-white">
                                @foreach($types as $type)
                                    <option value="{{ $type->code }}" {{ old('type_id', $vehicle->type_id) == $type->code ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- 3. Identification (Right) --}}
                    <div class="space-y-6">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-2 flex items-center">
                            <i class="ri-shield-keyhole-line mr-2 text-[#cb5c55]"></i> Status & ID
                        </h3>

                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Plate Number</label>
                                <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" class="w-full border-gray-300 rounded-lg shadow-sm uppercase font-mono focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 font-bold bg-gray-50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Custom ID</label>
                                <input type="text" name="vehicle_id_custom" value="{{ old('vehicle_id_custom', $vehicle->vehicle_id_custom) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 font-medium bg-gray-50">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ownership</label>
                            <select name="is_hasta_owned" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 font-medium bg-white">
                                <option value="1" {{ old('is_hasta_owned', $vehicle->is_hasta_owned) == '1' ? 'selected' : '' }}>Yes (Hasta Owned)</option>
                                <option value="0" {{ old('is_hasta_owned', $vehicle->is_hasta_owned) == '0' ? 'selected' : '' }}>No (Leased/External)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm font-bold text-gray-800 focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 bg-white">
                                <option value="Available" {{ old('status', $vehicle->status) == 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="Unavailable" {{ old('status', $vehicle->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                <option value="Maintenance" {{ old('status', $vehicle->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                    </div>

                    {{-- Ownership & Documents --}}
                    <div class="md:col-span-2 space-y-4 pt-4 border-t border-gray-100">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                             <i class="ri-folder-user-line text-[#cb5c55]"></i> Ownership & Documents
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Owner Name (Nama)</label>
                                <input type="text" name="owner_name" value="{{ old('owner_name', $vehicle->owner_name) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 font-medium" placeholder="Full Name of Owner">
                                <p class="text-xs text-gray-400 mt-1">Required if vehicle is leased from external owner.</p>
                            </div>
                            
                            <!-- File Uploads Group -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Owner IC</label>
                                    @if($vehicle->owner_ic_path)
                                        <div class="flex items-center justify-between mb-1">
                                            <a href="{{ asset('storage/' . $vehicle->owner_ic_path) }}" target="_blank" class="text-[10px] text-[#cb5c55] font-bold hover:underline bg-red-50 px-2 py-0.5 rounded border border-red-100"><i class="ri-eye-line"></i> View Current</a>
                                        </div>
                                    @endif
                                    <input type="file" name="owner_ic_path" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200 cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">License Owner</label>
                                    @if($vehicle->owner_license_path)
                                        <div class="flex items-center justify-between mb-1">
                                            <a href="{{ asset('storage/' . $vehicle->owner_license_path) }}" target="_blank" class="text-[10px] text-[#cb5c55] font-bold hover:underline bg-red-50 px-2 py-0.5 rounded border border-red-100"><i class="ri-eye-line"></i> View Current</a>
                                        </div>
                                    @endif
                                    <input type="file" name="owner_license_path" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200 cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Grant (Geran)</label>
                                    @if($vehicle->geran_path)
                                        <div class="flex items-center justify-between mb-1">
                                            <a href="{{ asset('storage/' . $vehicle->geran_path) }}" target="_blank" class="text-[10px] text-[#cb5c55] font-bold hover:underline bg-red-50 px-2 py-0.5 rounded border border-red-100"><i class="ri-eye-line"></i> View Current</a>
                                        </div>
                                    @endif
                                    <input type="file" name="geran_path" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200 cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Insurance Cover</label>
                                    @if($vehicle->insurance_cover_path)
                                        <div class="flex items-center justify-between mb-1">
                                            <a href="{{ asset('storage/' . $vehicle->insurance_cover_path) }}" target="_blank" class="text-[10px] text-[#cb5c55] font-bold hover:underline bg-red-50 px-2 py-0.5 rounded border border-red-100"><i class="ri-eye-line"></i> View Current</a>
                                        </div>
                                    @endif
                                    <input type="file" name="insurance_cover_path" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200 cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Pricing Configuration (Full Width - With Boundary!) --}}
                    @if($vehicle->pricingTier)
                    <div class="md:col-span-2 pt-4">

                        <div class="bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-6 relative">
                            
                            {{-- Header of the Box --}}
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-base font-bold text-gray-800 flex items-center">
                                        <i class="ri-price-tag-3-fill mr-2 text-[#cb5c55]"></i> Hourly Rates
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">Controlled by Tier: <span class="font-bold text-gray-700 bg-white border px-2 py-0.5 rounded">{{ $vehicle->pricingTier->name }}</span></p>
                                </div>
                                <a href="#" id="manage-rates-link" class="text-xs font-bold text-gray-500 hover:text-[#cb5c55] hover:underline flex items-center bg-white px-3 py-1.5 rounded border border-gray-200 shadow-sm transition">
                                    <i class="ri-settings-4-line mr-1"></i> Edit Tier Rules
                                </a>
                            </div>
                            
                            {{-- Grid Inputs --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                                @foreach([1, 3, 5, 7, 9, 12, 24] as $hour)
                                    @php
                                        $rate = $vehicle->pricingTier->rules->where('hour_limit', $hour)->first();
                                        $price = $rate ? $rate->price : '';
                                    @endphp
                                    <div class="group">
                                        <label class="block text-xs font-bold text-gray-500 mb-1.5 ml-1">{{ $hour }} Hour(s)</label>
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <span class="text-gray-400 font-bold text-xs">RM</span>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   name="rates[{{ $hour }}]" 
                                                   value="{{ $price }}" 
                                                   class="block w-full rounded-lg border-gray-300 bg-white pl-9 py-2.5 font-bold text-gray-800 shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] transition group-hover:border-gray-400" 
                                                   placeholder="0.00">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-gray-400 mt-4 flex items-center">
                                <i class="ri-information-line mr-1"></i> 
                                Note: Updates affect all vehicles in this tier.
                            </p>
                        </div>
                    </div>
                    @else
                    <div class="md:col-span-2 bg-yellow-50 p-6 rounded-xl border border-yellow-200 border-dashed text-center">
                        <i class="ri-alert-line text-2xl text-yellow-500 mb-2 inline-block"></i>
                        <h3 class="text-yellow-800 font-bold mb-1">No Custom Tier Assigned</h3>
                        <p class="text-xs text-yellow-600 mb-3">Assign a tier to enable pricing.</p>
                        <a href="{{ route('admin.pricing.index') }}" class="text-sm font-bold text-yellow-700 underline">Manage Tiers</a>
                    </div>
                    @endif

                    {{-- 5. Expiry & Fuel (Bottom) --}}
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Road Tax Expiry</label>
                            <input type="date" name="road_tax_expiry" value="{{ old('road_tax_expiry', $vehicle->road_tax_expiry) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Insurance Expiry</label>
                            <input type="date" name="insurance_expiry" value="{{ old('insurance_expiry', $vehicle->insurance_expiry) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2.5 px-4 font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Current Fuel</label>
                            <div class="flex items-center gap-3 pt-2 p-3 border border-gray-200 rounded-lg bg-gray-50">
                                <input type="range" name="current_fuel_bars" min="0" max="10" value="{{ old('current_fuel_bars', $vehicle->current_fuel_bars) }}" class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-[#cb5c55]" oninput="this.nextElementSibling.value = this.value">
                                <output class="text-lg font-black text-[#cb5c55] w-8 text-center">{{ $vehicle->current_fuel_bars }}</output> 
                                <span class="text-xs text-gray-500 font-bold uppercase">Bars</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('admin.vehicle.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition shadow-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#cb5c55] text-white font-bold rounded-lg hover:bg-[#b94a44] shadow-md transition transform hover:-translate-y-0.5 flex items-center">
                        <i class="ri-save-3-line mr-2"></i> Update Changes
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
            const link = document.getElementById('manage-rates-link');
            const tierId = "{{ $vehicle->pricing_tier_id }}";
            
            if (tierId && link) {
                const baseUrl = "{{ route('admin.pricing.edit', ':id') }}";
                link.href = baseUrl.replace(':id', tierId);
                link.classList.remove('hidden');
            } else if (link) {
                link.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', updateManageRatesLink);
    </script>
@endsection