<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Car List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.vehicle.create') }}" 
                            class="bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition" style="background-color:orange">
                            + Add New Vehicle
                        </a>
                    </div>
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-gray-100 uppercase font-medium text-gray-600">
                            <tr>
                                <th class="px-6 py-3 text-center">No.</th>
                                <th class="px-6 py-3 text-center">Custom ID</th>
                                <th class="px-6 py-3 text-center">Image</th>
                                <th class="px-6 py-3 text-center">Car Info</th>
                                <th class="px-6 py-3 text-center">Plate No.</th>
                                <th class="px-6 py-3 text-center">Fuel</th>
                                <th class="px-6 py-3 text-center">Price per Hour</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicle as $vehicles)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-center font-medium text-blue-600">
                                    {{ $vehicles->vehicle_id_custom }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($vehicles->vehicle_image)
                                        <img src="{{ asset('images/' . $vehicles->vehicle_image) }}" class="h-12 w-20 object-cover rounded">
                                    @else
                                        <span class="text-gray-400">No Image</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-bold">
                                    {{ $vehicles->brand }} {{ $vehicles->model }} <br>
                                    <span class="text-xs font-normal text-gray-500">{{ $vehicles->year }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">{{ $vehicles->plate_number }}</td>
                                <td class="px-6 py-4 text-center">{{ $vehicles->current_fuel_bars }}/10</td>
                                <td class="px-6 py-4 text-center">RM {{ $vehicles->price_per_hour }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-6 py-4 text-center rounded text-xs {{ $vehicles->status == 'Available' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ $vehicles->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
    <div class="flex justify-center items-center gap-4">
        
        <a href="{{ route('admin.vehicle.show', $vehicles->id) }}" 
           class="bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
            View Details
        </a>

        <form action="{{ route('admin.vehicle.destroy', $vehicles->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            
            <button type="submit" 
                    class="bg-red-600 text-white font-bold py-2 px-4 rounded-md hover:bg-red-700 shadow-md transition transform hover:-translate-y-0.5" style="padding:18px"
                    onclick="return confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')">
                Delete
            </button>
        </form>

    </div>
</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <script>
            window.onload = function() {
                alert("{{ session('success') }}");
            }
        </script>
    @endif
</x-app-layout>