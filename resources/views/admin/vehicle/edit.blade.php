<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Vehicle</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('admin.vehicle.update', $vehicle->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Brand</label>
                                <input type="text" name="brand" value="{{ $vehicle->brand }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Model</label>
                                <input type="text" name="model" value="{{ $vehicle->model }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Plate Number</label>
                                <input type="text" name="plate_number" value="{{ $vehicle->plate_number }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Price Per Hour (RM)</label>
                                <input type="number" step="0.01" name="price_per_hour" value="{{ $vehicle->price_per_hour }}" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Status</label>
                                <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    <option value="Available" {{ $vehicle->status == 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Unavailable" {{ $vehicle->status == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                    <option value="Maintenance" {{ $vehicle->status == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('admin.vehicle.index') }}" class="text-gray-600 underline mr-4">Cancel</a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                Update Vehicle
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>