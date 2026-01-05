@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex items-center gap-2 text-gray-500 text-sm text-left">
        <a href="{{ route('admin.dashboard') }}" class="hover:underline hover:text-[#cb5c55]">
            <i class="ri-arrow-left-line"></i> Back to Dashboard
        </a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Verify Payment #{{ $booking->id }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-xl shadow-sm col-span-2 h-fit border border-gray-100 text-left">
            <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Booking Information</h3>
            
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Customer</p>
                    
                    @if($booking->user)
                        <a href="{{ route('admin.customers.show', $booking->user->id) }}" class="font-semibold text-gray-800 text-lg hover:text-[#cb5c55] hover:underline decoration-dotted transition flex items-center gap-2 group">
                            {{ $booking->customer_name }}
                            <i class="ri-external-link-line text-gray-300 group-hover:text-[#cb5c55] text-xs"></i>
                        </a>
                    @else
                        <p class="font-semibold text-gray-800 text-lg">{{ $booking->customer_name }}</p>
                    @endif

                    <p class="text-sm text-gray-500">{{ $booking->customer_phone }}</p>
                    @if($booking->user)
                        <p class="text-sm text-gray-400">{{ $booking->user->email }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase font-bold">Status</p>
                    <span class="inline-block mt-1 px-4 py-1 text-sm font-bold text-blue-600 bg-blue-100 rounded-full">
                        {{ $booking->status }}
                    </span>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg flex gap-4 items-center border border-gray-200 mb-6">
                <img src="{{ asset('images/' . $booking->vehicle->vehicle_image) }}" class="w-32 h-20 object-cover rounded-md bg-white border">
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</h4>
                    <p class="text-sm text-gray-600 font-mono bg-gray-200 px-2 py-0.5 rounded inline-block">{{ $booking->vehicle->plate_number }}</p>
                </div>
            </div>

            @php
                $locations = [
                    'office'  => 'Student Mall',
                    'campus'  => 'In Campus',
                    'taman_u' => 'Taman Universiti',
                    'jb'      => 'Other Area JB',
                ];
                
                $pickupDisplay = $locations[$booking->pickup_location] ?? $booking->pickup_location;
                $dropoffDisplay = $locations[$booking->dropoff_location] ?? $booking->dropoff_location;
                
                if($booking->pickup_location != 'office' && $booking->custom_pickup_address) {
                    $pickupDisplay .= ' (' . $booking->custom_pickup_address . ')';
                }
                if($booking->dropoff_location != 'office' && $booking->custom_dropoff_address) {
                    $dropoffDisplay .= ' (' . $booking->custom_dropoff_address . ')';
                }
            @endphp

            <div class="grid grid-cols-2 gap-4">
                <div class="border p-4 rounded-lg bg-gray-50">
                    <p class="text-xs text-gray-400 font-bold uppercase mb-1">Pick-up</p>
                    <p class="font-medium text-gray-800">
                        {{ $booking->pickup_date_time ? \Carbon\Carbon::parse($booking->pickup_date_time)->format('d M Y, h:i A') : 'Not Set' }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1"><i class="ri-map-pin-line"></i> {{ $pickupDisplay }}</p>
                </div>
                <div class="border p-4 rounded-lg bg-gray-50">
                    <p class="text-xs text-gray-400 font-bold uppercase mb-1">Return</p>
                    <p class="font-medium text-gray-800">
                        {{ $booking->return_date_time ? \Carbon\Carbon::parse($booking->return_date_time)->format('d M Y, h:i A') : 'Not Set' }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1"><i class="ri-map-pin-line"></i> {{ $dropoffDisplay }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm flex flex-col h-full border-t-4 border-[#cb5c55] text-left">
            <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Payment Receipt</h3>

            <div class="w-full bg-gray-100 rounded-lg mb-4 border border-gray-300 relative group cursor-pointer overflow-hidden" 
                 onclick="window.open(this.querySelector('img') ? this.querySelector('img').src : '#')">
                
                @if($booking->payment_receipt)
                    <img src="{{ asset('storage/' . $booking->payment_receipt) }}" 
                         alt="Receipt" 
                         class="w-full h-auto rounded-lg shadow-sm transition-transform group-hover:scale-105">
                    
                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <span class="bg-black/60 text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-lg">Click to Zoom</span>
                    </div>
                @else
                    <div class="text-center py-10 text-gray-400">
                        <i class="ri-image-line text-4xl mb-2"></i>
                        <p class="text-xs font-bold uppercase tracking-widest">No receipt uploaded yet</p>
                    </div>
                @endif
            </div>
            
            @if($booking->payment_receipt)
                <div class="text-center mb-6">
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Total Fee (Inc. Deposit)</p>
                    <p class="text-3xl font-black text-[#cb5c55]">RM {{ number_format($booking->total_rental_fee + $booking->deposit_amount, 2) }}</p>
                </div>

                <div class="grid grid-cols-1 gap-3 mt-auto bg-white pt-2">
<button onclick="openRejectModal()" type="button" class="w-full py-4 bg-white text-red-600 font-bold rounded-xl border border-red-200 hover:bg-red-50 uppercase text-sm tracking-widest mb-3 shadow-sm transition-all hover:shadow-md">Reject</button>

                    <form action="{{ route('admin.payment.approve', $booking->id) }}" method="POST" onsubmit="return confirm('Approve this booking?');">
                        @csrf
                        <button class="w-full py-4 bg-[#cb5c55] text-white font-bold rounded-xl hover:bg-[#b04a44] shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 uppercase text-sm tracking-widest">Approve</button>
                    </form>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRejectModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <form action="{{ route('admin.payment.reject', $booking->id) }}" method="POST">
                @csrf
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="ri-close-circle-line text-red-600 text-xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Reject Booking</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Please provide a reason for rejecting this booking payment. This information will be sent to the customer.
                            </p>
                        </div>
                        <div class="mt-4">
                            <label for="modal_rejection_reason" class="sr-only">Rejection Reason</label>
                            <textarea name="rejection_reason" id="modal_rejection_reason" rows="3" class="w-full text-sm border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Enter reason here..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2">
                        Confirm Rejection
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1" onclick="closeRejectModal()">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") closeRejectModal();
    });
</script>
