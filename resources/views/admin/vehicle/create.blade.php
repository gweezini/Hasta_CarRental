<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Vehicle</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 mx-6 mt-6 rounded shadow-sm">
                    <p class="font-bold">Please fix the following errors:</p>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.vehicle.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2 border-t pt-4 col-span-2">
                                <label class="block font-medium text-sm text-gray-700">Vehicle Image</label>
                                <div>
                                    <input type="file" name="vehicle_image" accept="image/png, image/jpeg, image/jpg"
                                           class="block w-full text-sm text-gray-50 file:mr-4 file:py-2.5 file:px-6 file:rounded-md file:border-0
                                                  file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer transition icon-hover:shadow-md">
                                </div>
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Brand</label>
                                <input type="text" name="brand" value="{{ old('brand') }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Model</label>
                                <input type="text" name="model" value="{{ old('model') }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Plate Number</label>
                                <input type="text" name="plate_number" value="{{ old('plate_number') }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Year</label>
                                <input type="text" name="year" value="{{ old('year') }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>
                            
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Vehicle ID</label>
                                <input type="text" name="vehicle_id_custom" value="{{ old('vehicle_id_custom') }}" placeholder="e.g. V001"
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-1">Vehicle Type</label>
                                <select name="type_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    <option value="">-- Select Type --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->code }}" {{ old('type_id') == $type->code ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Capacity</label>
                                <input type="number" name="capacity" value="{{ old('capacity') }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-1">Is Hasta Owned?</label>
                                <select name="is_hasta_owned" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    <option value="1" {{ old('is_hasta_owned') == '1' ? 'selected' : '' }}>Yes (Hasta Owned)</option>
                                    <option value="0" {{ old('is_hasta_owned') == '0' ? 'selected' : '' }}>No (Leased/External)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Current Fuel Bars</label>
                                <input type="number" name="current_fuel_bars" value="{{ old('current_fuel_bars', 10) }}" max="10" min="0"
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Road Tax Expiry Date</label>
                                <input type="date" name="road_tax_expiry" value="{{ old('road_tax_expiry') }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Insurance Expiry Date</label>
                                <input type="date" name="insurance_expiry" value="{{ old('insurance_expiry') }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Price Per Hour (RM)</label>
                                <input type="number" step="0.01" name="price_per_hour" value="{{ old('price_per_hour') }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Status</label>
                                <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Unavailable" {{ old('status') == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                    <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 mb-4 flex justify-end items-center gap-4 mr-6">  
                            <a href="{{ route('admin.vehicle.index') }}" 
                               class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded-md hover:bg-gray-300 shadow-sm transition">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 text-white font-bold py-2 px-6 rounded-md hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                                Save New Vehicle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif
</x-app-layout>