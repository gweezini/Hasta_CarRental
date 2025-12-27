<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Vehicle</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.vehicle.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') 
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2 border-t pt-4">
                                <label class="block font-medium text-sm text-gray-700">Vehicle Image</label>
                                @if($vehicle->vehicle_image)
                                <img src="{{ asset('images/' . $vehicle->vehicle_image) }}" alt="Current Vehicle Image" class="w-48 h-32 rounded-lg shadow-sm object-cover border border-gray-200">
                            </div>
                                @else
                            <div class="mb-4 p-3 bg-gray-50 border rounded-xl inline-block text-gray-500 text-sm">
                                No image currently uploaded.
                            </div>
                                @endif
                            <div>
                                <input type="file" name="vehicle_image" accept="image/png, image/jpeg, image/jpg"
                                       class="block w-full text-sm text-gray-50 file:mr-4 file:py-2.5 file:px-6 file:rounded-md file:border-0
                                              file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer transition icon-hover:shadow-md">
                            </div>
                        </div>
                        <br>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Brand</label>
                                <input type="text" name="brand" value="{{ $vehicle->brand }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Model</label>
                                <input type="text" name="model" value="{{ $vehicle->model }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Plate Number</label>
                                <input type="text" name="plate_number" value="{{ $vehicle->plate_number }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Year</label>
                                <input type="text" name="year" value="{{ $vehicle->year }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Vehicle ID</label>
                                <input type="text" name="vehicle_id_custom" value="{{ $vehicle->vehicle_id_custom }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-1">Vehicle Type</label>
                                <select name="type_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    <option value="">-- Select Type --</option>
                                    @foreach($types as $type)
                                    <option value="{{ $type->code }}" {{ $vehicle->type_id == $type->code ? 'selected' : '' }}>{{ $type->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Capacity</label>
                                <input type="number" name="capacity" value="{{ $vehicle->capacity }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-1">Is Hasta Owned?</label>
                                <select name="is_hasta_owned" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    <option value="1" {{ $vehicle->is_hasta_owned == 1 ? 'selected' : '' }}>
                                        Yes (Hasta Owned)
                                    </option>
                                    <option value="0" {{ $vehicle->is_hasta_owned == 0 ? 'selected' : '' }}>
                                        No (Leased/External)
                                    </option>
                                </select>
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Current Fuel Bars</label>
                                <input type="number" name="current_fuel_bars" value="{{ $vehicle->current_fuel_bars }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Road Tax Expiry Date</label>
                                    <input type="date" name="road_tax_expiry" value="{{ $vehicle->road_tax_expiry }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Insurance Expiry Date</label>
                                    <input type="date" name="insurance_expiry" value="{{ $vehicle->insurance_expiry }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Price Per Hour (RM)</label>
                                <input type="number" step="0.01" name="price_per_hour" value="{{ $vehicle->price_per_hour }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>
                            <br>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Status</label>
                                <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    <option value="Available" {{ $vehicle->status == 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Unavailable" {{ $vehicle->status == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                    <option value="Maintenance" {{ $vehicle->status == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
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
                                Update Vehicle
                            </button>
                        </div>
                    </form>
                    @if(session('success'))
                    <script>
                    alert("{{ session('success') }}");
                    </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>