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
                    
                    @if(session('success'))
                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-gray-100 uppercase font-medium text-gray-600">
                            <tr>
                                <th class="p-3">ID</th>
                                <th class="p-3">Image</th>
                                <th class="p-3">Car Info</th>
                                <th class="p-3">Plate No.</th>
                                <th class="p-3">Price/Hr</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicle as $vehicles)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $loop->iteration }}</td>
                                <td class="p-3">
                                    @if($vehicles->vehicle_image)
                                        <img src="{{ asset('images/' . $vehicles->vehicle_image) }}" class="h-12 w-20 object-cover rounded">
                                    @else
                                        <span class="text-gray-400">No Image</span>
                                    @endif
                                </td>
                                <td class="p-3 font-bold">
                                    {{ $vehicles->brand }} {{ $vehicles->model }} <br>
                                    <span class="text-xs font-normal text-gray-500">{{ $vehicles->year }}</span>
                                </td>
                                <td class="p-3">{{ $vehicles->plate_number }}</td>
                                <td class="p-3">RM {{ $vehicles->price_per_hour }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-xs {{ $vehicles->status == 'Available' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ $vehicles->status }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <a href="{{ route('admin.vehicle.edit', $vehicles->id) }}" 
                                       class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs" style="background-color:blue">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>