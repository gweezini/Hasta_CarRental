@extends('layouts.admin')

@section('header_title', 'Booking Details #' . $booking->id)

@section('content')
<div class="mb-6 text-left">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.bookings.index') }}" class="text-sm font-bold text-gray-500 hover:text-[#cd5c5c] transition flex items-center gap-2">
            <i class="ri-arrow-left-line"></i> Back to History
        </a>
        @if($booking->feedback)
            <a href="#feedback-section" class="text-xs font-black text-indigo-500 hover:text-indigo-700 uppercase tracking-widest flex items-center gap-1">
                <i class="ri-chat-smile-3-fill"></i> Jump to Feedback
            </a>
        @endif
        @if($booking->inspections->count() > 0)
            <a href="#inspection-section" class="text-xs font-black text-[#cb5c55] hover:text-[#b04a44] uppercase tracking-widest flex items-center gap-1">
                <i class="ri-search-eye-line"></i> Jump to Inspection
            </a>
        @endif
    </div>
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

    {{-- FULL WIDTH SECTION FOR INSPECTIONS & FEEDBACK --}}
    <div class="lg:col-span-3 space-y-8 mt-6 border-t border-gray-100 pt-8">
        
        {{-- 0. FINES & PENALTIES --}}
        <div id="fines-section" class="scroll-mt-24">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-800 tracking-tight flex items-center gap-2">
                    <i class="ri-alert-fill text-red-500"></i> Fines & Penalties
                </h3>
                <button onclick="document.getElementById('fineModal').classList.remove('hidden')" 
                        class="bg-red-50 text-red-600 hover:bg-red-100 font-bold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2 border border-red-200 shadow-sm">
                    <i class="ri-add-line"></i> Issue Fine
                </button>
            </div>

            @if($booking->fines->count() > 0)
                <div class="bg-white rounded-xl border border-red-100 shadow-sm overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-red-50 text-red-800 uppercase text-[10px] font-black tracking-widest border-b border-red-100">
                            <tr>
                                <th class="px-6 py-4">Reason</th>
                                <th class="px-6 py-4">Amount</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($booking->fines as $fine)
                                <tr class="hover:bg-red-50/30 transition">
                                    <td class="px-6 py-4 font-bold text-gray-800">{{ $fine->reason }}</td>
                                    <td class="px-6 py-4 font-mono text-gray-600">RM {{ number_format($fine->amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        @if($fine->status == 'Paid')
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-green-200">
                                                Paid
                                            </span>
                                            <div class="text-[10px] text-gray-400 mt-1">
                                                {{ $fine->paid_at ? \Carbon\Carbon::parse($fine->paid_at)->format('d M Y') : '' }}
                                            </div>
                                        @else
                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-red-200">
                                                Unpaid
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center flex items-center justify-center gap-2">
                                        @if($fine->status == 'Unpaid')
                                            <form action="{{ route('admin.fines.pay', $fine->id) }}" method="POST" onsubmit="return confirm('Confirm fine collection?')">
                                                @csrf
                                                <button type="submit" class="bg-green-500 text-white text-[10px] font-black px-4 py-2 rounded-lg shadow hover:bg-green-600 transition uppercase tracking-widest transform hover:scale-105">
                                                    Mark Paid
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.fines.destroy', $fine->id) }}" method="POST" onsubmit="return confirm('Delete this fine record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-white text-gray-400 border border-gray-200 hover:text-red-500 hover:border-red-200 hover:bg-red-50 font-bold text-[10px] px-3 py-2 rounded-lg transition uppercase shadow-sm flex items-center justify-center" title="Delete record">
                                                <i class="ri-delete-bin-line text-sm"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-8 text-center text-gray-400">
                    <i class="ri-shield-check-line text-3xl mb-2 text-green-500"></i>
                    <p class="text-xs font-bold uppercase tracking-widest">Clean Record - No Fines</p>
                </div>
            @endif
        </div>
        
        {{-- 1. INSPECTION REPORTS --}}
        @if($booking->inspections->count() > 0)
            <div id="inspection-section" class="scroll-mt-24">
                <h3 class="text-xl font-black text-gray-800 tracking-tight mb-6 flex items-center gap-2">
                    <i class="ri-search-eye-line text-[#cb5c55]"></i> Inspection Reports
                </h3>
                
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    @foreach($booking->inspections as $inspection)
                        <div class="relative">
                            {{-- Badge for Type --}}
                            <div class="absolute -top-3 -right-3 z-10">
                                <span class="px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest shadow-sm border
                                    {{ $inspection->type == 'pickup' ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : 'bg-green-100 text-green-700 border-green-200' }}">
                                    {{ ucfirst($inspection->type) }} Verification
                                </span>
                            </div>
                            @include('inspections.partials.report_card', ['inspection' => $inspection])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 2. CUSTOMER FEEDBACK --}}
        @if($booking->feedback)
            <div id="feedback-section" class="bg-indigo-50/50 rounded-3xl p-8 border border-indigo-100 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                        <i class="ri-feedback-fill text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-indigo-900 tracking-tight">Customer Feedback</h3>
                        <p class="text-xs text-indigo-500 font-bold uppercase tracking-widest">Submitted on {{ $booking->feedback->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Ratings --}}
                    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $ratings = $booking->feedback->ratings ?? [];
                            $categories = [
                                'cleanliness_interior' => 'Interior Cleanliness',
                                'smell' => 'Smell / Freshness',
                                'cleanliness_exterior' => 'Exterior Appearance',
                                'cleanliness_trash' => 'Trash Free',
                                'condition_mechanical' => 'Mechanical Condition',
                                'condition_ac' => 'A/C Performance',
                                'condition_fuel' => 'Fuel Level / Battery',
                                'condition_safety' => 'Safety Features',
                                'service_access' => 'Ease of Access',
                                'service_value' => 'Value for Money'
                            ];
                        @endphp

                        @foreach($categories as $key => $label)
                            @if(isset($ratings[$key]))
                                <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-indigo-100 shadow-sm">
                                    <span class="text-sm font-bold text-gray-600">{{ $label }}</span>
                                    @if($key === 'smell')
                                        <span class="text-sm font-bold text-indigo-700">{{ $ratings[$key] }}</span>
                                    @else
                                        <div class="flex gap-1">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="ri-star-fill text-xs {{ $i <= $ratings[$key] ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Comment --}}
                    <div class="bg-white p-6 rounded-2xl border border-indigo-100 shadow-sm min-h-[200px]">
                         <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Written Review</p>
                         <p class="text-gray-700 italic leading-relaxed">
                            "{{ $booking->feedback->description ?: 'No written comment provided.' }}"
                         </p>
                    </div>
                </div>
            </div>
        @endif

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

{{-- Fine Modal --}}
<div id="fineModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('fineModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.bookings.fine.store', $booking->id) }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="ri-alert-line text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Issue Fine / Penalty</h3>
                            <div class="mt-2 text-sm text-gray-500">
                                <p>This will be recorded in the booking details.</p>
                            </div>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                                    <input type="text" name="reason" id="reason" required placeholder="e.g. Minor scratch on bumper, Late return (2 hours)"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount (RM)</label>
                                    <input type="number" step="0.01" name="amount" id="amount" required placeholder="0.00"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Issue Fine
                    </button>
                    <button type="button" onclick="document.getElementById('fineModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
