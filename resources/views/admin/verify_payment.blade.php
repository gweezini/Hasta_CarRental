@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex items-center gap-2 text-gray-500 text-sm">
        <a href="{{ route('admin.dashboard') }}" class="hover:underline hover:text-[#cb5c55]">
            <i class="ri-arrow-left-line"></i> Back to Dashboard
        </a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Verify Payment #{{ $booking->id }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-xl shadow-sm col-span-2 h-fit border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Booking Information</h3>
            
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold">Customer</p>
                    <p class="font-semibold text-gray-800 text-lg">{{ $booking->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->user->phone_number }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase font-bold">Current Status</p>
                    <span class="inline-block mt-1 px-4 py-1 text-sm font-bold text-blue-600 bg-blue-100 rounded-full border border-blue-200">
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

            <div class="grid grid-cols-2 gap-4">
                <div class="border p-4 rounded-lg bg-gray-50">
                    <p class="text-xs text-gray-400 font-bold uppercase mb-1">Pick-up Date & Time</p>
                    <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, h:i A') }}</p>
                    <p class="text-sm text-gray-500 mt-1"><i class="ri-map-pin-line"></i> {{ $booking->pickup_location }}</p>
                </div>
                <div class="border p-4 rounded-lg bg-gray-50">
                    <p class="text-xs text-gray-400 font-bold uppercase mb-1">Return Date & Time</p>
                    <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, h:i A') }}</p>
                    <p class="text-sm text-gray-500 mt-1"><i class="ri-map-pin-line"></i> {{ $booking->dropoff_location }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm flex flex-col h-full border-t-4 border-[#cb5c55]">
            <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Payment Receipt</h3>

            <div class="flex-grow flex items-center justify-center bg-gray-100 rounded-lg mb-4 overflow-hidden border border-gray-300 relative group cursor-pointer" 
                 onclick="window.open(this.querySelector('img') ? this.querySelector('img').src : '#')"
                 title="Click to view full image">
                
                @if($payment && $payment->receipt_image)
                    <img src="{{ asset('storage/' . $payment->receipt_image) }}" alt="Receipt" class="object-contain max-h-80 w-full group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-center justify-center">
                        <span class="text-white opacity-0 group-hover:opacity-100 font-bold bg-black/50 px-3 py-1 rounded">
                            <i class="ri-zoom-in-line"></i> View
                        </span>
                    </div>
                @else
                    <div class="text-center py-10 text-gray-400">
                        <i class="ri-image-line text-4xl mb-2"></i>
                        <p>No receipt uploaded yet</p>
                    </div>
                @endif
            </div>
            
            @if($payment)
                <div class="text-center mb-6">
                    <p class="text-sm text-gray-500 uppercase font-bold">Total Amount Paid</p>
                    <p class="text-3xl font-bold theme-text">RM {{ number_format($payment->amount, 2) }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <form action="{{ route('admin.payment.reject', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to REJECT this booking?');">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-white text-red-600 font-bold rounded-lg hover:bg-red-50 transition border border-red-200 shadow-sm flex items-center justify-center gap-2">
                            <i class="ri-close-circle-line text-xl"></i> Reject
                        </button>
                    </form>

                    <form action="{{ route('admin.payment.approve', $booking->id) }}" method="POST" onsubmit="return confirm('Confirm payment and APPROVE booking?');">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-[#cb5c55] text-white font-bold rounded-lg hover:opacity-90 transition shadow-lg flex items-center justify-center gap-2">
                            <i class="ri-checkbox-circle-line text-xl"></i> Approve
                        </button>
                    </form>
                </div>
            @else
                <div class="text-center text-amber-600 font-medium bg-amber-50 p-4 rounded border border-amber-200">
                    <i class="ri-loader-4-line animate-spin mr-1"></i> Waiting for customer to pay...
                </div>
            @endif
        </div>

    </div>
</div>
@endsection