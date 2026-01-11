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
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-full text-xs font-black uppercase tracking-widest border border-gray-200 transition flex items-center gap-2">
                        <i class="ri-edit-line"></i> Modify
                    </a>
                    <span class="px-4 py-2 rounded-full text-xs font-black border {{ $style }} uppercase tracking-widest shadow-sm">
                        {{ $booking->status }}
                    </span>
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
                
                {{-- Emergency & Bank Details (Added) --}}
                <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 pt-6">
                     <div>
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-2 flex items-center gap-1">
                            <i class="ri-alarm-warning-line text-orange-500"></i> Emergency Contact
                        </p>
                        <p class="font-bold text-gray-800 text-base">{{ $booking->emergency_contact_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->emergency_contact_phone ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 mt-1 italic">{{ $booking->emergency_relationship ?? 'Relationship not specified' }}</p>
                     </div>

                     <div>
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-2 flex items-center gap-1">
                            <i class="ri-bank-card-line text-blue-500"></i> Banking Details
                        </p>
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                             <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Bank Name</p>
                             <p class="font-bold text-gray-800 mb-2">{{ $booking->refund_bank_name ?? 'N/A' }}</p>
                             
                             <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Account Number</p>
                             <p class="font-mono text-gray-800 mb-2">{{ $booking->refund_account_number ?? 'N/A' }}</p>
                             
                             <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Recipient Name</p>
                             <p class="text-sm text-gray-800">{{ $booking->refund_recipient_name ?? 'N/A' }}</p>
                        </div>
                     </div>
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

            <div class="text-center mb-6 space-y-6">
                <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-6">
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Rental Fee</p>
                        <p class="text-lg font-bold text-gray-700">RM {{ number_format($booking->total_rental_fee, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Security Deposit</p>
                        <p class="text-lg font-bold text-gray-700">RM {{ number_format($booking->deposit_amount, 2) }}</p>
                    </div>
                </div>
                
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Total Amount Paid</p>
                    <p class="text-4xl font-black text-[#cb5c55]">RM {{ number_format($booking->total_rental_fee + $booking->deposit_amount, 2) }}</p>
                </div>
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

        {{-- 0. FINES & PENALTIES --}}
        <div id="fines-section" class="scroll-mt-24 border-t border-gray-100 pt-8">
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h3 class="text-xl font-black text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="ri-alert-fill text-red-500"></i> Fines & Penalties
                    </h3>
                    @if($booking->total_fines > 0)
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                            Total Fines: <span class="text-red-600">RM {{ number_format($booking->total_fines, 2) }}</span>
                        </p>
                    @endif
                </div>
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
                                            <span class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-5 py-2 rounded-xl text-sm font-black uppercase tracking-widest border border-green-200 shadow-sm">
                                                <i class="ri-checkbox-circle-fill text-lg"></i> PAID
                                            </span>
                                            <div class="text-xs text-green-600 mt-2 font-bold pl-1">
                                                Verified: {{ $fine->paid_at ? \Carbon\Carbon::parse($fine->paid_at)->format('d M Y, h:i A') : '' }}
                                            </div>
                                        @elseif($fine->status == 'Pending Verification')
                                            <span class="inline-flex items-center gap-1.5 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-yellow-200 animate-pulse">
                                                <i class="ri-loader-4-line animate-spin"></i> Verifying
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-red-200">
                                                <i class="ri-close-circle-fill"></i> Unpaid
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($fine->receipt_path)
                                                <button onclick="openFullImage('{{ asset('storage/' . $fine->receipt_path) }}')" 
                                                        class="bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 p-2 rounded-lg transition" 
                                                        title="View Payment Receipt">
                                                    <i class="ri-receipt-line text-lg"></i>
                                                </button>
                                            @endif
    
                                            @if($fine->status == 'Pending Verification')
                                                <form action="{{ route('admin.fines.verify', $fine->id) }}" method="POST" onsubmit="return confirm('Confirm payment verification?')">
                                                    @csrf
                                                    <button type="submit" class="bg-green-500 text-white text-[10px] font-black px-4 py-2.5 rounded-lg shadow hover:bg-green-600 transition uppercase tracking-widest flex items-center gap-2 transform hover:scale-105">
                                                        <i class="ri-check-line text-sm"></i> Verify
                                                    </button>
                                                </form>
                                            @elseif($fine->status == 'Unpaid')
                                                <form action="{{ route('admin.fines.verify', $fine->id) }}" method="POST" onsubmit="return confirm('Mark as Paid manually?')">
                                                    @csrf
                                                    <button type="submit" class="bg-gray-100 text-gray-600 text-[10px] font-bold px-3 py-2 rounded-lg hover:bg-green-500 hover:text-white transition uppercase tracking-widest border border-gray-200">
                                                        Mark Paid
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
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

        {{-- 0.5 DEPOSIT RETURN --}}
        <div id="deposit-section" class="scroll-mt-24 border-t border-gray-100 pt-8">
            <h3 class="text-xl font-black text-gray-800 tracking-tight mb-6 flex items-center gap-2">
                <i class="ri-hand-coin-line text-blue-500"></i> Deposit Status
            </h3>

            <div class="bg-white rounded-xl border border-blue-50 shadow-sm p-8">
                @if($booking->deposit_status == 'Returned')
                    <div class="flex items-center gap-4 bg-green-50 p-6 rounded-2xl border border-green-100 text-green-700 font-bold mb-6">
                        <i class="ri-checkbox-circle-fill text-2xl"></i>
                        <div>
                            <p class="text-lg">Deposit Processed & Returned</p>
                            <p class="text-xs font-normal text-green-600">Transaction completed on {{ \Carbon\Carbon::parse($booking->deposit_returned_at)->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                     
                    @if($booking->deposit_receipt_path)
                         <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-3">Proof of Return</p>
                         <div class="w-64 h-40 bg-gray-100 rounded-2xl overflow-hidden cursor-pointer border border-gray-200 shadow-sm group" onclick="window.open('{{ asset('storage/' . $booking->deposit_receipt_path) }}', '_blank')">
                             <img src="{{ asset('storage/' . $booking->deposit_receipt_path) }}" class="w-full h-full object-cover group-hover:opacity-90 transition transform group-hover:scale-105 duration-500">
                         </div>
                    @endif

                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Calculation Summary --}}
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-4">Settlement Summary</p>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500 font-medium">Original Security Deposit</span>
                                        <span class="font-bold text-gray-800">RM {{ number_format($booking->deposit_amount, 2) }}</span>
                                    </div>
                                    
                                    @if($booking->fines->count() > 0)
                                        <div class="pt-2 pb-1">
                                            <p class="text-[9px] text-gray-400 font-black uppercase tracking-wider mb-2">Detailed Deductions</p>
                                            <div class="space-y-2">
                                                @foreach($booking->fines as $fine)
                                                    <div class="flex justify-between items-center text-[11px]">
                                                        <span class="text-gray-400 truncate pr-4">{{ $fine->reason }}</span>
                                                        <span class="font-bold text-red-500 shrink-0">- RM {{ number_format($fine->amount, 2) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex justify-between items-center text-sm text-red-600 border-t border-gray-100 pt-2">
                                        <span class="font-black uppercase text-[10px]">Total Penalties</span>
                                        <span class="font-black">- RM {{ number_format($booking->total_fines, 2) }}</span>
                                    </div>
                                    <div class="pt-3 border-t-2 border-dashed border-gray-200 flex justify-between items-center">
                                        @if($booking->net_refund > 0)
                                            <span class="text-sm font-black text-gray-800 uppercase tracking-tight">Net Refund Amount</span>
                                            <span class="text-xl font-black text-blue-600">RM {{ number_format($booking->net_refund, 2) }}</span>
                                        @else
                                            <span class="text-sm font-black text-gray-800 uppercase tracking-tight">Deposit Fully Consumed</span>
                                            <span class="text-xl font-black text-gray-400">RM 0.00</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($booking->outstanding_balance > 0)
                                <div class="bg-red-50 border border-red-100 rounded-2xl p-6 flex items-start gap-4">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-600 shrink-0">
                                        <i class="ri-error-warning-fill text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-red-800 font-black uppercase text-[10px] tracking-widest mb-1">Action Required</p>
                                        <p class="text-sm text-red-700 font-bold mb-1">Customer owes an additional balance!</p>
                                        <p class="text-xl font-black text-red-600">RM {{ number_format($booking->outstanding_balance, 2) }}</p>
                                        <p class="text-xs text-red-500/80 mt-2 italic">Deposit was fully used to cover penalties. Customer must pay the remaining amount manually.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Return Action --}}
                        <div class="flex flex-col justify-center">
                            @if($booking->net_refund > 0)
                                <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-6">
                                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-3">Process Refund</p>
                                    <p class="text-xs text-gray-600 mb-6 leading-relaxed">Please return the <b>RM {{ number_format($booking->net_refund, 2) }}</b> and upload the receipt (JPG, PNG, or PDF).</p>
                                    
                                    <form action="{{ route('admin.bookings.return_deposit', $booking->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="space-y-4">
                                            <div>
                                                <input type="file" name="deposit_receipt" required accept="image/*,.pdf" 
                                                       class="block w-full text-sm text-gray-500 border border-gray-200 rounded-lg bg-white file:mr-4 file:py-3 file:px-4 file:rounded-l-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200 transition cursor-pointer shadow-sm">
                                            </div>
                                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl text-xs uppercase tracking-widest shadow-lg shadow-blue-100 transition transform hover:-translate-y-0.5">
                                                Complete Refund
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 text-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                        <i class="ri-forbid-line text-3xl"></i>
                                    </div>
                                    <h4 class="font-black text-gray-800 uppercase text-xs tracking-widest mb-2">No Refund Applicable</h4>
                                    <p class="text-sm text-gray-500 leading-relaxed">The security deposit has been fully consumed to cover issued penalties. No funds remain to be returned.</p>
                                    
                                    @if($booking->deposit_status != 'Forfeited')
                                        <form action="{{ route('admin.bookings.forfeit_deposit', $booking->id) }}" method="POST" class="mt-6">
                                            @csrf
                                            <button type="submit" class="text-[10px] font-black text-gray-400 hover:text-red-500 uppercase tracking-widest underline decoration-dotted transition">
                                                Mark Deposit as Fully Deducted
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. CUSTOMER FEEDBACK --}}
        @if($booking->feedback)
            <div id="feedback-section" class="bg-indigo-50/50 rounded-3xl p-8 border border-indigo-100 scroll-mt-24">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                            <i class="ri-feedback-fill text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-indigo-900 tracking-tight">Customer Feedback</h3>
                            <p class="text-xs text-indigo-500 font-bold uppercase tracking-widest">Submitted on {{ $booking->feedback->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    @php
                        $ratings = $booking->feedback->ratings ?? [];
                        $isNewStyle = false;
                        foreach($ratings as $k => $v) { if(strpos($k, 'issue_') === 0) { $isNewStyle = true; break; } }
                        $issueCount = $isNewStyle ? count(array_filter($ratings, fn($v) => $v === true)) : 0;
                    @endphp
                    @if($issueCount > 0)
                        <div class="animate-bounce inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg border-2 border-white">
                            <i class="ri-alert-fill text-lg"></i> Maintenance Needed
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Ratings / Issues --}}
                    <div class="lg:col-span-2">
                        @php
                            $newCategories = [
                                'issue_interior' => ['label' => 'Cleanliness/Trash', 'icon' => 'ri-brush-2-line'],
                                'issue_smell' => ['label' => 'Bad Smell', 'icon' => 'ri-windy-line'],
                                'issue_mechanical' => ['label' => 'Mechanical/Noise', 'icon' => 'ri-settings-4-line'],
                                'issue_ac' => ['label' => 'A/C Problem', 'icon' => 'ri-temp-cold-line'],
                                'issue_exterior' => ['label' => 'Dirty Exterior', 'icon' => 'ri-car-washing-line'],
                                'issue_safety' => ['label' => 'Safety Concern', 'icon' => 'ri-shield-cross-line'],
                            ];
                            
                            $oldCategories = [
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

                        @if($isNewStyle)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($newCategories as $key => $data)
                                    @php $hasIssue = $ratings[$key] ?? false; @endphp
                                    <div class="flex items-center justify-between p-4 {{ $hasIssue ? 'bg-orange-50 border-orange-200' : 'bg-white border-indigo-50' }} rounded-xl border shadow-sm transition">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg {{ $hasIssue ? 'bg-orange-100 text-orange-600' : 'bg-gray-50 text-gray-400' }} flex items-center justify-center">
                                                <i class="{{ $data['icon'] }} text-lg"></i>
                                            </div>
                                            <span class="text-sm font-bold {{ $hasIssue ? 'text-orange-800' : 'text-gray-500' }}">{{ $data['label'] }}</span>
                                        </div>
                                        @if($hasIssue)
                                            <span class="px-2 py-1 bg-orange-600 text-white text-[10px] font-black rounded uppercase">Issue Reported</span>
                                        @else
                                            <i class="ri-checkbox-circle-fill text-green-500 text-xl"></i>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($oldCategories as $key => $label)
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
                        @endif
                    </div>

                    {{-- Comment --}}
                    <div class="bg-white p-6 rounded-2xl border border-indigo-100 shadow-sm flex flex-col">
                         <div class="flex items-center gap-2 mb-4 text-indigo-600">
                             <i class="ri-chat-4-line text-lg"></i>
                             <p class="text-xs font-black uppercase tracking-widest">Customer Comments</p>
                         </div>
                         <div class="flex-grow p-4 bg-gray-50 rounded-xl border border-gray-100">
                             <p class="text-gray-700 italic leading-relaxed text-sm">
                                "{{ $booking->feedback->description ?: 'No written comment provided.' }}"
                             </p>
                         </div>
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
function openFullImage(src = null) {
    const overlay = document.getElementById('imageOverlay');
    const fullImg = document.getElementById('fullImage');
    
    if (src) {
        fullImg.src = src;
    } else {
        const receiptImg = document.getElementById('receiptImg');
        if (!receiptImg) return;
        fullImg.src = receiptImg.src;
    }

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
