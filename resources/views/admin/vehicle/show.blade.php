@extends('layouts.admin')

@section('header_title')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.vehicle.index') }}" class="text-gray-400 hover:text-[#cb5c55] transition" title="Back to Fleet">
            <i class="ri-arrow-left-line text-xl align-middle"></i>
        </a>
        <span class="align-middle">Vehicle Details</span>
    </div>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 p-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 uppercase tracking-tight">{{ $vehicle->brand }} {{ $vehicle->model }}</h1>
                <p class="text-gray-500 font-medium mt-1 flex items-center gap-2">
                    <span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded text-xs font-mono uppercase">{{ $vehicle->plate_number }}</span>
                    <span>• {{ $vehicle->year }} Model</span>
                </p>
            </div>
            
            <div>
                @if($vehicle->status == 'Available')
                    <span class="px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider bg-green-100 text-green-700 border border-green-200">
                        <i class="ri-checkbox-circle-line mr-1"></i> Available
                    </span>
                @elseif($vehicle->status == 'Maintenance')
                    <span class="px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider bg-yellow-100 text-yellow-700 border border-yellow-200">
                        <i class="ri-tools-line mr-1"></i> Maintenance
                    </span>
                @else
                    <span class="px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider bg-red-100 text-red-700 border border-red-200">
                        <i class="ri-prohibited-line mr-1"></i> {{ $vehicle->status }}
                    </span>
                @endif
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 shadow-sm text-center">
                        @if($vehicle->vehicle_image)
                            <img src="{{ asset('images/' . $vehicle->vehicle_image) }}" 
                                 class="w-full h-auto rounded-lg shadow-md object-cover hover:scale-[1.02] transition duration-300">
                        @else
                            <div class="h-64 flex flex-col items-center justify-center text-gray-400 font-medium bg-gray-100 rounded-lg border-2 border-dashed border-gray-300">
                                <i class="ri-image-line text-4xl mb-2"></i>
                                <span>No Image Available</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <h4 class="text-xs font-bold text-blue-800 uppercase mb-3">Ownership Status</h4>
                        @if($vehicle->is_hasta_owned)
                            <div class="flex items-center gap-2 text-green-700 font-bold">
                                <i class="ri-shield-check-fill text-xl"></i>
                                <span>Hasta Owned Asset</span>
                            </div>
                            <p class="text-xs text-green-600 mt-1 pl-7">Managed internally by Hasta fleet team.</p>
                        @else
                            <div class="flex items-center gap-2 text-orange-600 font-bold">
                                <i class="ri-shake-hands-fill text-xl"></i>
                                <span>Leased / External</span>
                            </div>
                            <p class="text-xs text-orange-600 mt-1 pl-7">Owned by external partner.</p>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2 flex items-center gap-2">
                        <i class="ri-settings-3-line text-[#cb5c55]"></i> Vehicle Specifications
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Vehicle ID</span>
                            <span class="text-base font-medium text-gray-800 bg-gray-100 px-2 py-1 rounded">{{ $vehicle->vehicle_id_custom }}</span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Vehicle Type</span>
                            <span class="text-base font-medium text-gray-800">{{ $vehicle->type->name ?? $vehicle->type_id }}</span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Seating Capacity</span>
                            <span class="text-base font-medium text-gray-800 flex items-center gap-2">
                                <i class="ri-user-line text-gray-400"></i> {{ $vehicle->capacity }} Passengers
                            </span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Rental Rate</span>
                            <span class="text-xl font-bold text-[#cb5c55]">RM {{ number_format($vehicle->price_per_hour, 2) }} <span class="text-sm text-gray-500 font-normal">/ hour</span></span>
                        </div>

                        <div class="md:col-span-2">
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Current Fuel Level</span>
                            <div class="flex items-center gap-4">
                                <div class="flex-1 bg-gray-200 rounded-full h-3">
                                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $vehicle->current_fuel_bars * 10 }}%"></div>
                                </div>
                                <span class="text-sm font-bold text-blue-700 w-16 text-right">{{ $vehicle->current_fuel_bars }}/10 Bars</span>
                            </div>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-2 gap-6 pt-6 border-t border-gray-50">
                            <div>
                                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Road Tax Expiry</span>
                                <span class="text-base font-medium {{ \Carbon\Carbon::parse($vehicle->road_tax_expiry)->isPast() ? 'text-red-600' : 'text-gray-800' }}">
                                    {{ \Carbon\Carbon::parse($vehicle->road_tax_expiry)->format('d M Y') }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Insurance Expiry</span>
                                <span class="text-base font-medium {{ \Carbon\Carbon::parse($vehicle->insurance_expiry)->isPast() ? 'text-red-600' : 'text-gray-800' }}">
                                    {{ \Carbon\Carbon::parse($vehicle->insurance_expiry)->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end gap-4">
                        <a href="{{ route('admin.vehicle.index') }}" 
                           class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                            Back to List
                        </a>
                        <a href="{{ route('admin.vehicle.edit', $vehicle->id) }}" 
                           class="px-6 py-2.5 bg-[#cb5c55] text-white font-bold rounded-lg hover:bg-[#b04a43] shadow-md transition transform hover:-translate-y-0.5 flex items-center gap-2">
                            <i class="ri-pencil-line"></i> Edit Vehicle
                        </a>
                    </div>

                </div>
            </div>
            {{-- Maintenance History Section --}}
            <div class="mt-8 border-t border-gray-100 pt-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="ri-tools-line text-[#cb5c55]"></i> Maintenance History
                    </h3>
                    <button onclick="document.getElementById('maintenanceModal').classList.remove('hidden')" 
                            class="text-base font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-5 py-2.5 rounded-lg transition shadow-sm border border-blue-100">
                        + Add Record
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-[10px] uppercase text-gray-400 font-bold tracking-wider border-b">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Description</th>
                                <th class="px-6 py-4">Recorded By</th>
                                <th class="px-6 py-4 text-right">Cost (RM)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($vehicle->maintenanceLogs->sortByDesc('date') as $log)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-mono text-gray-600">
                                        {{ \Carbon\Carbon::parse($log->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-800">
                                        {{ $log->description }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $log->user->name ?? 'System' }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-[#cb5c55]">
                                        {{ number_format($log->cost, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">
                                        No maintenance records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($vehicle->maintenanceLogs->count() > 0)
                            <tfoot class="bg-gray-50 border-t">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-600 uppercase text-xs tracking-wider">Total Cost</td>
                                    <td class="px-6 py-4 text-right font-black text-lg text-[#cb5c55]">
                                        {{ number_format($vehicle->maintenanceLogs->sum('cost'), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

        </div> 
    </div>
@endsection

{{-- Maintenance Modal --}}
<div id="maintenanceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('maintenanceModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.vehicle.maintenance.store', $vehicle->id) }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Record Maintenance</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" name="date" id="date" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <input type="text" name="description" id="description" required placeholder="e.g. Oil Change, Tire Replacement"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="cost" class="block text-sm font-medium text-gray-700">Cost (RM)</label>
                                    <input type="number" step="0.01" name="cost" id="cost" required placeholder="0.00"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#cb5c55] text-base font-medium text-white hover:bg-[#b04a43] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Record
                    </button>
                    <button type="button" onclick="document.getElementById('maintenanceModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>