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

                <div class="grid grid-cols-2 gap-3 mt-auto bg-white pt-2">
                    <form action="{{ route('admin.payment.reject', $booking->id) }}" method="POST" onsubmit="return confirm('Reject this booking?');">
                        @csrf
                        <button class="w-full py-3 bg-white text-red-600 font-bold rounded-lg border border-red-200 hover:bg-red-50 uppercase text-xs tracking-widest">Reject</button>
                    </form>

                    <form action="{{ route('admin.payment.approve', $booking->id) }}" method="POST" onsubmit="return confirm('Approve this booking?');">
                        @csrf
                        <button class="w-full py-3 bg-[#cb5c55] text-white font-bold rounded-lg hover:opacity-90 uppercase text-xs tracking-widest">Approve</button>
                    </form>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection