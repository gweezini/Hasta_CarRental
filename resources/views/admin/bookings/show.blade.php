@extends('layouts.admin')

@section('header_title', 'Booking Details #' . $booking->id)

@section('content')
<div class="mb-6 text-left">
    <a href="{{ route('admin.bookings.index') }}" class="text-sm font-bold text-gray-500 hover:text-[#cd5c5c] transition flex items-center gap-2">
        <i class="ri-arrow-left-line"></i> Back to History
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-left">
            <div class="flex justify-between items-start mb-8">
                <h3 class="text-xl font-black text-gray-800 tracking-tight">Booking Information</h3>
                
                @php
                    $style = match($booking->status) {
                        'Approved' => 'bg-green-100 text-green-700 border-green-200',
                        'Rejected' => 'bg-red-100 text-red-700 border-red-200',
                        'Completed' => 'bg-purple-100 text-purple-700 border-purple-200',
                        default => 'bg-blue-100 text-blue-700 border-blue-200'
                    };
                @endphp
                <span class="px-4 py-2 rounded-full text-xs font-black border {{ $style }} uppercase tracking-widest shadow-sm">
                    {{ $booking->status }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
              
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Customer</p>
                    
                    @if($booking->user)
                        <a href="{{ route('admin.customers.show', $booking->user->id) }}" class="font-bold text-gray-900 text-lg hover:text-[#cb5c55] hover:underline decoration-dotted transition flex items-center gap-2 group">
                            {{ $booking->customer_name }}
                            <i class="ri-external-link-line text-gray-300 group-hover:text-[#cb5c55] text-sm"></i>
                        </a>
                    @else
                        <p class="font-bold text-gray-900 text-lg">{{ $booking->customer_name }}</p>
                    @endif

                    <p class="text-sm text-gray-500">{{ $booking->customer_phone }}</p>
                    @if($booking->user)
                        <p class="text-sm text-gray-400 mt-1 italic">{{ $booking->user->email }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-2xl flex gap-6 items-center border border-gray-200 mb-8">
                <div class="w-32 h-20 bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm flex items-center justify-center">
                    <img src="{{ asset('images/' . $booking->vehicle->vehicle_image) }}" class="object-contain w-full h-full p-1">
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-xl">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</h4>
                    <p class="text-sm text-gray-600 font-mono bg-gray-200 px-3 py-1 rounded-md inline-block mt-1 tracking-wider">
                        {{ $booking->vehicle->plate_number }}
                    </p>
                </div>
            </div>

            {{-- Pick-up & Return --}}
            @php
                $locations = [
                    'office'  => 'Student Mall',
                    'campus'  => 'In Campus (KTF0)',
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border p-5 rounded-xl bg-gray-50/50 border-gray-200">
                    <p class="text-[10px] text-gray-400 font-black uppercase mb-1 tracking-widest">Pick-up</p>
                    <p class="font-bold text-gray-800">
                        {{ $booking->pickup_date_time ? \Carbon\Carbon::parse($booking->pickup_date_time)->format('d M Y, h:i A') : 'Not Set' }}
                    </p>
                    <p class="text-sm text-gray-500 mt-2 flex items-start gap-1">
                        <i class="ri-map-pin-2-fill text-[#cb5c55] shrink-0"></i> {{ $pickupDisplay }}
                    </p>
                </div>
                <div class="border p-5 rounded-xl bg-gray-50/50 border-gray-200">
                    <p class="text-[10px] text-gray-400 font-black uppercase mb-1 tracking-widest">Return</p>
                    <p class="font-bold text-gray-800">
                        {{ $booking->return_date_time ? \Carbon\Carbon::parse($booking->return_date_time)->format('d M Y, h:i A') : 'Not Set' }}
                    </p>
                    <p class="text-sm text-gray-500 mt-2 flex items-start gap-1">
                        <i class="ri-map-pin-2-fill text-[#cb5c55] shrink-0"></i> {{ $dropoffDisplay }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col h-full border-t-4 border-[#cb5c55]">
            <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2 text-left">Payment Receipt</h3>

            <div class="w-full bg-gray-50 rounded-xl mb-6 border border-gray-200 relative group cursor-pointer overflow-hidden" onclick="openFullImage()">
                @if($booking->payment_receipt)
                    <img id="receiptImg" src="{{ asset('storage/' . $booking->payment_receipt) }}" 
                         alt="Receipt" class="w-full h-auto rounded-lg transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="bg-black/60 text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-lg">Click to Enlarge</span>
                    </div>
                @else
                    <div class="text-center py-16 text-gray-400">
                        <i class="ri-image-2-line text-5xl mb-3 block"></i>
                        <p class="text-xs font-bold uppercase tracking-widest">No receipt uploaded yet</p>
                    </div>
                @endif
            </div>

            <div class="text-center mb-6">
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Total Rental Fee</p>
                <p class="text-4xl font-black text-[#cb5c55]">RM {{ number_format($booking->total_rental_fee, 2) }}</p>
            </div>

            <div class="mt-auto space-y-4">
                @if($booking->status == 'Approved' || $booking->status == 'Completed')
                    <div class="w-full py-4 bg-gray-100 text-gray-400 font-black rounded-xl border border-gray-200 text-sm uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="ri-checkbox-circle-fill text-green-500 text-lg"></i>
                        Approved by {{ $booking->processedBy->name ?? 'System' }}
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold text-center uppercase tracking-tighter">
                        PROCESSED AT: {{ $booking->updated_at->format('d M Y, h:i A') }}
                    </p>
                @elseif($booking->status == 'Rejected')
                    <div class="w-full py-4 bg-gray-50 text-red-400 font-black rounded-xl border border-red-100 text-sm uppercase tracking-widest flex items-center justify-center gap-2 italic">
                        <i class="ri-close-circle-fill text-red-400 text-lg"></i>
                        Rejected by {{ $booking->processedBy->name ?? 'System' }}
                    </div>
                @else
                    <div class="w-full py-4 bg-blue-50 text-blue-400 font-black rounded-xl border border-blue-100 text-sm uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="ri-time-fill text-lg"></i>
                        Waiting for Verification
                    </div>
                @endif
            </div>
        </div>


    </div>
</div>

<div id="imageOverlay" class="fixed inset-0 bg-black/95 z-[100] hidden flex items-center justify-center p-6 cursor-zoom-out" onclick="closeFullImage()">
    <img id="fullImage" src="" class="max-w-full max-h-full object-contain shadow-2xl transition-transform duration-300 transform scale-95">
</div>

<script>
function openFullImage() {
    const receiptImg = document.getElementById('receiptImg');
    if (!receiptImg) return;
    const overlay = document.getElementById('imageOverlay');
    const fullImg = document.getElementById('fullImage');
    fullImg.src = receiptImg.src;
    overlay.classList.remove('hidden');
    setTimeout(() => {
        fullImg.classList.remove('scale-95');
        fullImg.classList.add('scale-100');
    }, 10);
}
function closeFullImage() {
    const fullImg = document.getElementById('fullImage');
    fullImg.classList.remove('scale-100');
    fullImg.classList.add('scale-95');
    setTimeout(() => {
        document.getElementById('imageOverlay').classList.add('hidden');
    }, 200);
}
document.addEventListener('keydown', function(e) {
    if (e.key === "Escape") closeFullImage();
});
</script>
@endsection