@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pricing Management</h1>
        <a href="{{ route('admin.pricing.create') }}" class="inline-flex items-center px-4 py-2 bg-[#cb5c55] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#b04a44] active:bg-[#963f3a] focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
            <i class="ri-add-line mr-2 text-lg"></i> Create New Tier
        </a>
    </div>

    @if($tiers->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="h-20 w-20 bg-red-50 text-[#cb5c55] rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-price-tag-3-line text-4xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">No Pricing Tiers Found</h3>
            <p class="text-gray-500 mb-6">Get started by creating your first vehicle pricing tier.</p>
            <a href="{{ route('admin.pricing.create') }}" class="inline-flex items-center px-4 py-2 bg-[#cb5c55] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#b04a44] transition shadow-sm">
                Create Tier
            </a>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($tiers as $tier)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-200">
            <div class="p-6">
                <div class="flex justify-between items-start cursor-pointer group" onclick="document.getElementById('tier-modal-{{ $tier->id }}').classList.remove('hidden')">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide group-hover:text-[#cb5c55] transition flex items-center gap-2">
                            {{ $tier->name }}
                            <i class="ri-information-line text-gray-300 group-hover:text-[#cb5c55] text-sm"></i>
                        </h3>
                        <p class="text-sm text-gray-500 font-medium mt-1 group-hover:text-gray-700">
                            Vehicles: {{ $tier->vehicles()->count() }}
                        </p>
                    </div>
                    <div class="h-10 w-10 bg-red-50 text-[#cb5c55] rounded-lg flex items-center justify-center group-hover:bg-[#cb5c55] group-hover:text-white transition">
                        <i class="ri-price-tag-3-line text-xl"></i>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Current Rates</h4>
                    <ul class="space-y-2 text-sm">
                        @foreach($tier->rules->sortBy('hour_limit') as $rule)
                            <li class="flex justify-between items-center py-1 border-b border-gray-50 last:border-0 hover:bg-gray-50 px-2 -mx-2 rounded transition">
                                <span class="text-gray-600 font-medium">{{ $rule->hour_limit }} Hour(s)</span>
                                <span class="font-bold text-gray-800">RM {{ number_format($rule->price, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-6">
                    <a href="{{ route('admin.pricing.edit', $tier->id) }}" class="block w-full text-center py-2.5 bg-[#cb5c55] text-white font-bold text-sm rounded-lg hover:bg-[#b04a44] transition shadow-sm">
                        Edit Prices
                    </a>
                </div>
            </div>
        </div>

        {{-- Vehicle List Modal --}}
        <div id="tier-modal-{{ $tier->id }}" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('tier-modal-{{ $tier->id }}').classList.add('hidden')"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-xl sm:w-full border border-gray-100">
                    <div class="bg-[#cb5c55] px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-black text-white uppercase tracking-wider" id="modal-title">
                            {{ $tier->name }} - Vehicles
                        </h3>
                        <button type="button" class="text-white hover:text-gray-200 transition" onclick="document.getElementById('tier-modal-{{ $tier->id }}').classList.add('hidden')">
                            <i class="ri-close-line text-2xl"></i>
                        </button>
                    </div>
                    
                    <div class="px-6 py-6 max-h-[60vh] overflow-y-auto">
                        @if($tier->vehicles->count() > 0)
                            <div class="grid grid-cols-1 gap-4">
                                @foreach($tier->vehicles as $vehicle)
                                    <a href="{{ route('admin.vehicle.show', $vehicle->id) }}" class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 bg-gray-50 hover:border-[#cb5c55] hover:bg-red-50 transition group cursor-pointer decoration-transparent">
                                        <div class="h-12 w-12 bg-white rounded-lg border border-gray-200 p-1 flex items-center justify-center shrink-0">
                                            @if($vehicle->vehicle_image)
                                                <img src="{{ asset('images/' . $vehicle->vehicle_image) }}" class="w-full h-full object-contain">
                                            @else
                                                <i class="ri-car-fill text-gray-300 text-xl"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-800 group-hover:text-[#cb5c55] transition">{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                                            <p class="text-xs text-gray-500 font-mono">{{ $vehicle->plate_number }}</p>
                                        </div>
                                        <div>
                                            <i class="ri-arrow-right-s-line text-gray-300 group-hover:text-[#cb5c55] transition text-xl"></i>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10 text-gray-400">
                                <i class="ri-car-line text-4xl mb-2"></i>
                                <p class="text-sm">No vehicles assigned to this tier.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('tier-modal-{{ $tier->id }}').classList.add('hidden')">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
