@extends('layouts.admin')

@section('header_title', 'Fleet Management')

@section('content')
    {{-- Unified Page Header --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Fleet Management</h2>
        <p class="text-gray-500 text-sm">Monitor and maintain your vehicle inventory and pricing tiers</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
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
                        <th class="p-4 border-b">Price Rate</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($vehicle as $index => $v)
                    @php
                        $expired = ($v->road_tax_expiry && \Carbon\Carbon::parse($v->road_tax_expiry)->isPast()) || ($v->insurance_expiry && \Carbon\Carbon::parse($v->insurance_expiry)->isPast());
                        $unset = is_null($v->road_tax_expiry) || is_null($v->insurance_expiry);
                        $expiring = !$expired && !$unset && (
                            (\Carbon\Carbon::parse($v->road_tax_expiry)->lte(now()->addDays(30))) || 
                            (\Carbon\Carbon::parse($v->insurance_expiry)->lte(now()->addDays(30)))
                        );
                    @endphp
                    <tr class="transition group {{ $expired ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }}">
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
                            
                            @if($expired)
                               <span class="inline-flex items-center gap-1 mt-1 text-[10px] text-red-600 font-black uppercase tracking-wider bg-red-100 px-2 py-0.5 rounded">
                                   <i class="ri-alarm-warning-fill"></i> Docs Expired
                               </span>
                            @elseif($unset)
                               <span class="inline-flex items-center gap-1 mt-1 text-[10px] text-orange-600 font-black uppercase tracking-wider bg-orange-100 px-2 py-0.5 rounded">
                                   <i class="ri-error-warning-fill"></i> Docs Missing
                               </span>
                            @elseif($expiring)
                               <span class="inline-flex items-center gap-1 mt-1 text-[10px] text-yellow-700 font-black uppercase tracking-wider bg-yellow-100 px-2 py-0.5 rounded">
                                   <i class="ri-time-line"></i> Expiring Soon
                               </span>
                            @endif
                        </td>
                        <td class="p-4 font-mono text-gray-600">{{ $v->plate_number }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-1">
                                <i class="ri-gas-station-fill text-gray-400"></i>
                                <span class="font-medium">{{ $v->current_fuel_bars }}/10</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <button onclick="openRateModal({{ $v->id }}, '{{ $v->pricingTier ? $v->pricingTier->name : 'Default' }}', '{{ $v->brand }} {{ $v->model }}', {{ $v->pricingTier ? $v->pricingTier->rules->toJson() : '[]' }}, '{{ $v->pricingTier ? route('admin.pricing.edit', $v->pricingTier->id) : '' }}', '{{ route('admin.vehicle.edit', $v->id) }}')" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm font-bold flex items-center gap-2">
                                <i class="ri-eye-line"></i> View
                            </button>
                        </td>
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

    <!-- Rate View Modal -->
    <div id="rateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all scale-95" id="rateModalContent">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="modalVehicleName">Vehicle Rates</h3>
                    <p class="text-xs text-gray-500" id="modalTierName">Tier Name</p>
                </div>
                <button onclick="closeRateModal()" class="text-gray-400 hover:text-gray-600 transition p-1 hover:bg-gray-200 rounded-full">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <!-- Rates Table -->
                <div id="modalRatesContent">
                    <div class="overflow-hidden rounded-lg border border-gray-200">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Duration</th>
                                    <th scope="col" class="px-4 py-3 text-right">Price (RM)</th>
                                </tr>
                            </thead>
                            <tbody id="modalRatesBody" class="bg-white divide-y divide-gray-100">
                                <!-- Populated via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- No Tier Message -->
                <div id="modalNoTierMessage" class="hidden text-center py-6">
                    <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg inline-block mb-3">
                        <i class="ri-alert-line text-2xl mb-1 block"></i>
                        <span class="font-bold block">No Custom Tier Assigned</span>
                    </div>
                    <p class="text-sm text-gray-600">This vehicle is using the default legacy flat rate.</p>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeRateModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Close
                </button>
                <a id="modalAssignBtn" href="#" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-sm hidden">
                    <i class="ri-link mr-1"></i> Assign Tier
                </a>
            </div>
        </div>
    </div>

    <!-- Schedule Modal -->
    <div id="scheduleModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all scale-95 flex flex-col max-h-[90vh]">
            <div class="bg-purple-50 px-6 py-4 border-b border-purple-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="scheduleVehicleTitle">Vehicle Schedule</h3>
                    <p class="text-xs text-purple-600">Upcoming Bookings</p>
                </div>
                <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600 transition p-1 hover:bg-purple-200 rounded-full">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            
            <div class="p-0 overflow-y-auto flex-1 relative min-h-[300px]">
                <!-- Loading State -->
                <div id="scheduleLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-white z-10">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-purple-600 mb-3"></div>
                    <p class="text-sm text-gray-500 font-medium">Fetching schedule...</p>
                </div>

                <!-- Content -->
                <div id="scheduleContent" class="hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                            <tr class="text-xs uppercase text-gray-500 font-bold">
                                <th class="px-4 py-3 border-b">Time Range</th>
                                <th class="px-4 py-3 border-b">Customer</th>
                                <th class="px-4 py-3 border-b text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTableBody" class="divide-y divide-gray-100 text-sm">
                            <!-- Populated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
                <button onclick="closeScheduleModal()" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function openRateModal(vehicleId, tierName, vehicleName, rates, editUrl, assignUrl) {
            document.getElementById('modalVehicleName').textContent = vehicleName + ' Rates';
            document.getElementById('modalTierName').textContent = tierName;
            
            const tbody = document.getElementById('modalRatesBody');
            tbody.innerHTML = '';
            
            const modalContent = document.getElementById('modalRatesContent');
            const noTierMsg = document.getElementById('modalNoTierMessage');
            // const editBtn = document.getElementById('modalEditBtn'); // Removed
            const assignBtn = document.getElementById('modalAssignBtn');

            if (rates && rates.length > 0) {
                // Determine logic for displaying rates properly
                // Sort by hour_limit
                rates.sort((a, b) => a.hour_limit - b.hour_limit);

                rates.forEach(rate => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50';
                    tr.innerHTML = `
                        <td class="px-4 py-3 font-medium text-gray-900">${rate.hour_limit} Hours</td>
                        <td class="px-4 py-3 text-right font-bold text-[#cb5c55]">${parseFloat(rate.price).toFixed(2)}</td>
                    `;
                    tbody.appendChild(tr);
                });
                
                modalContent.classList.remove('hidden');
                noTierMsg.classList.add('hidden');
                
                // if (editUrl) {
                //     editBtn.href = editUrl;
                //     editBtn.classList.remove('hidden');
                // } else {
                //     editBtn.classList.add('hidden');
                // }
                assignBtn.classList.add('hidden');

            } else {
                modalContent.classList.add('hidden');
                noTierMsg.classList.remove('hidden');
                
                // editBtn.classList.add('hidden');
                assignBtn.href = assignUrl;
                assignBtn.classList.remove('hidden');
            }

            const modal = document.getElementById('rateModal');
            modal.classList.remove('hidden');
            // Small timeout to allow transition
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95');
                modal.firstElementChild.classList.add('scale-100');
            }, 10);
        }

        function closeRateModal() {
            const modal = document.getElementById('rateModal');
            modal.firstElementChild.classList.remove('scale-100');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // Schedule Modal Functions
        function openScheduleModal(vehicleId) {
            const modal = document.getElementById('scheduleModal');
            const loading = document.getElementById('scheduleLoading');
            const content = document.getElementById('scheduleContent');
            const tbody = document.getElementById('scheduleTableBody');
            const vehicleTitle = document.getElementById('scheduleVehicleTitle');

            // Reset state
            tbody.innerHTML = '';
            loading.classList.remove('hidden');
            content.classList.add('hidden');
            vehicleTitle.textContent = 'Loading...';

            // Show modal
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95');
                modal.firstElementChild.classList.add('scale-100');
            }, 10);

            // Fetch data
            fetch(`/admin/vehicle/${vehicleId}/schedule`)
                .then(response => response.json())
                .then(data => {
                    vehicleTitle.textContent = data.vehicle_name;
                    loading.classList.add('hidden');
                    content.classList.remove('hidden');

                    if (data.bookings.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center w-full">
                                        <i class="ri-calendar-check-line text-4xl text-gray-300 mb-2"></i>
                                        <p>No upcoming bookings found.</p>
                                        <p class="text-xs text-gray-400 mt-1">This vehicle is free for now!</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                    } else {
                        data.bookings.forEach(booking => {
                            const startDate = new Date(booking.pickup_date_time).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit' });
                            const endDate = new Date(booking.return_date_time).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit' });
                            
                            let statusColor = 'bg-gray-100 text-gray-700';
                            if(booking.status === 'Approved') statusColor = 'bg-green-100 text-green-700';
                            if(booking.status === 'Rented') statusColor = 'bg-blue-100 text-blue-700';
                            if(booking.status === 'Waiting for Verification') statusColor = 'bg-orange-100 text-orange-700';

                            const tr = document.createElement('tr');
                            tr.className = 'border-b hover:bg-gray-50';
                            tr.innerHTML = `
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                    ${startDate}
                                    <div class="text-xs text-gray-400">to ${endDate}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">${booking.customer_name}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 text-xs font-bold rounded-full ${statusColor}">${booking.status}</span>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loading.innerHTML = '<p class="text-red-500 text-center">Failed to load schedule.</p>';
                });
        }

        function closeScheduleModal() {
            const modal = document.getElementById('scheduleModal');
            modal.firstElementChild.classList.remove('scale-100');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }
    </script>
@endsection
