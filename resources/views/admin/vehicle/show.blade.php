<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vehicle Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="bg-gray-50 border-b border-gray-200 flex justify-between items-center" style="padding: 25px;">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 uppercase">{{ $vehicle->brand }} {{ $vehicle->model }}</h1>
                        <p class="text-sm text-gray-500 font-bold mt-1 tracking-wide">{{ $vehicle->plate_number }} • {{ $vehicle->year }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider
                        {{ $vehicle->status == 'Available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $vehicle->status }}
                    </span>
                </div>

                <div style="padding: 50px;">

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-10">

                        <div class="sm:col-span-1 flex flex-col gap-6">
                                @if($vehicle->vehicle_image)
                                    <img src="{{ asset('images/' . $vehicle->vehicle_image) }}" 
                                     style="display: block; margin: 0 auto; width: 500px; height: auto; border-radius: 15px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);">
                                @else
                                    <div class="h-48 flex items-center justify-center text-gray-400 font-bold bg-gray-100 rounded-lg">
                                        No Image
                                    </div>
                                @endif
                        </div>
                        <br>
                        <div class="sm:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-2" style="font-size: 25px;">Vehicle Specifications</h3>
                            
                            <div class="grid grid-cols-2 gap-y-6 gap-x-8">
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Vehicle ID</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $vehicle->vehicle_id_custom }}</span>
                                </div>
                                <br>
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Type ID</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $vehicle->type_id }}</span>
                                </div>
                                <br>
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Capacity</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $vehicle->capacity }} Passengers</span>
                                </div>
                                <br>
                                <div class="mb-2">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Current Fuel</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="ml-3 text-xs font-bold text-blue-600 whitespace-nowrap text-right">
                                        {{ $vehicle->current_fuel_bars }}/10 Bars
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3" style="max-width: 300px;">
                                    <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $vehicle->current_fuel_bars * 10 }}%; background-color: #2563eb"></div>
                                </div>
                                <br>
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Ownership</span>
                                    @if($vehicle->is_hasta_owned)
                                        <span class="text-green-600 font-bold text-sm bg-green-50 px-2 py-1 rounded inline-flex">
                                            ✔ Hasta Owned
                                        </span>
                                    @else
                                        <span class="text-orange-500 font-bold text-sm bg-orange-50 px-2 py-1 rounded inline-flex">
                                            External Owner
                                        </span>
                                    @endif
                                </div>
                                <br>
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Roadtax Expiry Date</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $vehicle->road_tax_expiry }}</span>
                                </div>
                                <br>
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Insurance Expiry Date</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $vehicle->insurance_expiry }}</span>
                                </div>
                                <br>
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Price per Hour</span>
                                    <span class="text-base font-semibold text-gray-800">RM {{ $vehicle->price_per_hour }} </span>
                                </div>
                            </div>

                            <div class="mt-10 flex justify-end pt-6 border-t border-gray-100">
                                <a href="{{ route('admin.vehicle.index') }}" style="margin-right: 20px; padding: 12px 40px; font-size: 16px;"
                                   class="bg-white border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                                   Back
                                </a>
                                <a href="{{ route('admin.vehicle.edit', $vehicle->id) }}" style="padding: 12px 40px; font-size: 16px;"
                                   class="bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 shadow-md transition">
                                   Edit Vehicle
                                </a>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>
        </div>
    </div>
</x-app-layout>