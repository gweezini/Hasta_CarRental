@extends('layouts.admin')

@section('header_title', 'Fleet Management')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Car List</h2>
            <p class="text-gray-500 text-sm">Manage your vehicle fleet</p>
        </div>
        
        <a href="{{ route('admin.vehicle.create') }}" class="flex items-center gap-2 px-5 py-3 bg-[#cb5c55] text-white rounded-lg shadow hover:opacity-90 transition font-medium">
            <i class="ri-add-circle-line text-xl"></i> Add New Vehicle
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center">
            <i class="ri-checkbox-circle-line mr-2 text-xl"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase font-bold tracking-wider">
                        <th class="p-4 border-b">No.</th>
                        <th class="p-4 border-b">Custom ID</th>
                        <th class="p-4 border-b">Image</th>
                        <th class="p-4 border-b">Car Info</th>
                        <th class="p-4 border-b">Plate No.</th>
                        <th class="p-4 border-b">Fuel</th>
                        <th class="p-4 border-b">Price/Hr</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($vehicle as $index => $v)
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="p-4 text-gray-500">{{ $index + 1 }}</td>
                        <td class="p-4 font-medium text-[#cb5c55]">{{ $v->vehicle_id_custom }}</td>
                        <td class="p-4">
                            <div class="w-16 h-10 rounded bg-gray-100 overflow-hidden border border-gray-200">
                                <img src="{{ asset('images/' . $v->vehicle_image) }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-gray-800">{{ $v->brand }} {{ $v->model }}</div>
                            <div class="text-xs text-gray-500">{{ $v->year }}</div>
                        </td>
                        <td class="p-4 font-mono text-gray-600">{{ $v->plate_number }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-1">
                                <i class="ri-gas-station-fill text-gray-400"></i>
                                <span class="font-medium">{{ $v->current_fuel_bars }}/10</span>
                            </div>
                        </td>
                        <td class="p-4 font-bold text-gray-800">RM {{ number_format($v->price_per_hour, 2) }}</td>
                        <td class="p-4">
                            @if($v->status === 'Available')
                                <span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">Available</span>
                            @elseif($v->status === 'Maintenance')
                                <span class="px-3 py-1 text-xs font-bold text-yellow-700 bg-yellow-100 rounded-full">Maintenance</span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full">{{ $v->status }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.vehicle.show', $v->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition" title="View">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('admin.vehicle.edit', $v->id) }}" class="p-2 bg-orange-50 text-orange-600 rounded hover:bg-orange-100 transition" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('admin.vehicle.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Delete this vehicle?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 rounded hover:bg-red-100 transition" title="Delete">
                                        <i class="ri-delete-bin-line"></i>
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
@endsection