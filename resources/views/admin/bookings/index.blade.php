@extends('layouts.admin')

@section('header_title', 'All Bookings')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 bg-gray-50/30">
            <h3 class="text-lg font-bold text-gray-800 tracking-tight">Full Booking History</h3>
        </div>
        
        <div class="overflow-x-auto text-left">
            <table class="w-full text-sm text-gray-600">
                <thead class="bg-gray-50 text-[10px] uppercase text-gray-400 font-black tracking-widest border-b">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Vehicle</th>
                        <th class="px-6 py-4">Dates</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition cursor-pointer" onclick="window.location='{{ route('admin.bookings.show_detail', $booking->id) }}'">
                       
                        <td class="px-6 py-5 font-bold text-gray-400 text-xs">
                            <a href="{{ route('admin.bookings.show_detail', $booking->id) }}" class="hover:text-[#cb5c55] transition underline decoration-dotted">
                                #{{ $booking->id }}
                            </a>
                        </td>
                        
                        <td class="px-6 py-5">
                            <a href="{{ route('admin.customers.show', $booking->user->id) }}" onclick="event.stopPropagation()" class="text-base font-bold text-gray-900 hover:text-[#cb5c55] hover:underline transition block leading-tight">
                                {{ $booking->user->name }}
                            </a>
                            <p class="text-xs text-gray-500 font-medium mt-1">
                                <i class="ri-phone-line mr-1"></i>{{ $booking->customer_phone ?? $booking->user->phone ?? 'No Phone' }}
                            </p>
                        </td>

                        <td class="px-6 py-5">
                            <p class="font-bold text-gray-800 uppercase leading-tight">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                            <p class="text-xs text-gray-400 font-mono mt-0.5 tracking-wider">{{ $booking->vehicle->plate_number }}</p>
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100 w-fit">
                                    FROM: {{ $booking->pickup_date_time->format('d M, h:i A') }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-600 bg-gray-50 px-2 py-0.5 rounded border border-gray-200 w-fit">
                                    TO: {{ $booking->return_date_time->format('d M, h:i A') }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center gap-3">
                                @php
                                    $style = match($booking->status) {
                                        'Approved' => 'bg-green-100 text-green-700 border-green-200',
                                        'Rejected' => 'bg-red-100 text-red-700 border-red-200',
                                        'Waiting for Verification', 'Verify Receipt' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'Completed' => 'bg-purple-100 text-purple-700 border-purple-200',
                                        'Pending', 'pending' => 'bg-gray-100 text-gray-500 border-gray-200',
                                        default => 'bg-gray-100 text-gray-400'
                                    };
                                @endphp

                                <span class="px-3 py-1.5 rounded-full text-[10px] font-black border {{ $style }} uppercase tracking-widest shadow-sm">
                                    {{ $booking->status == 'Waiting for Verification' ? 'Verify Receipt' : $booking->status }}
                                </span>

                                @if($booking->processedBy)
                                    <span class="text-[11px] text-gray-400 font-bold uppercase tracking-tight whitespace-nowrap">
                                        by {{ $booking->processedBy->name }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-5 text-center">
                            @if($booking->status == 'Waiting for Verification' || $booking->status == 'Verify Receipt')
                                <a href="{{ route('admin.payment.verify', $booking->id) }}" onclick="event.stopPropagation()" class="inline-block bg-[#cd5c5c] text-white text-[11px] font-black px-5 py-2 rounded-lg shadow hover:bg-[#b04a45] transition uppercase tracking-widest">Verify</a>
                            
                            @elseif($booking->status == 'Approved')
                                <div class="flex items-center justify-center gap-2">
                                    @if($booking->inspections->count() > 0)
                                        <a href="{{ route('inspections.show', $booking->inspections->first()) }}" onclick="event.stopPropagation()" class="bg-white text-blue-600 border-2 border-blue-200 hover:bg-blue-50 hover:border-blue-300 font-black text-[10px] px-3 py-2 rounded-lg transition-all flex items-center gap-1 uppercase shadow-sm h-full whitespace-nowrap">
                                            <i class="ri-eye-line text-sm"></i> Inspection
                                        </a>
                                    @endif
                                    
                                    @php
                                        $hasReturnInspection = $booking->inspections->contains('type', 'return');
                                    @endphp
                                    
                                    @if($hasReturnInspection)
                                    <form action="{{ route('admin.booking.return', $booking->id) }}" method="POST" onsubmit="return confirm('Confirm vehicle return?')" onclick="event.stopPropagation()">
                                        @csrf
                                        <button type="submit" class="bg-white text-green-700 border-2 border-green-500 hover:bg-green-500 hover:text-white font-black text-[10px] px-3 py-2 rounded-lg transition-all flex items-center gap-1 uppercase shadow-sm h-full whitespace-nowrap">
                                            <i class="ri-checkbox-circle-line"></i> Return
                                        </button>
                                    </form>
                                    @else
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest border border-gray-200 px-3 py-2 rounded-lg bg-gray-50 cursor-not-allowed" title="Waiting for return inspection">
                                            Wait Return
                                        </span>
                                    @endif
                                </div>
                            
                            @elseif($booking->status == 'Completed')
                                <div class="flex items-center justify-center text-purple-400 gap-1 font-black text-[11px] uppercase tracking-widest">
                                    <i class="ri-checkbox-circle-fill text-xl text-purple-500"></i> Done
                                </div>
                            
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-100">
            {{ $bookings->links() }}
        </div>
    </div>

    <div id="bookingModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-black/60 transition-opacity" onclick="closeBookingModal()"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-4xl w-full overflow-hidden grid grid-cols-1 md:grid-cols-2 text-left">
                <div class="p-8 border-r border-gray-100">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-2xl font-black text-gray-800" id="m-id">#000</h3>
                        <span id="m-status" class="px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-widest shadow-sm"></span>
                    </div>
                    <div class="space-y-5">
                        <div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer</label><p id="m-customer" class="font-bold text-gray-800 text-lg"></p></div>
                        <div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Vehicle</label><p id="m-vehicle" class="font-bold text-gray-800"></p></div>
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Fee</label>
                            <p class="text-2xl font-black text-[#cb5c55]">RM <span id="m-total">0.00</span></p>
                        </div>
                        <div class="border-t pt-4">
                            <label class="text-[10px] font-black text-blue-500 uppercase block mb-1 tracking-widest">Audit Trail</label>
                            <p class="text-xs font-bold text-gray-600 italic">Action by: <span id="m-processed-by"></span></p>
                            <p class="text-[10px] text-gray-400 font-medium" id="m-processed-at"></p>
                        </div>
                    </div>
                </div>
                <div class="p-8 bg-gray-50 flex flex-col items-center justify-center text-center">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block w-full">Payment Receipt</label>
                    <img id="m-img" src="" onclick="openLargeImage(this.src)" class="max-h-[350px] rounded-lg shadow-md border-4 border-white cursor-zoom-in hover:scale-[1.02] transition">
                    <p class="mt-3 text-[10px] text-gray-400 italic">Click image to enlarge</p>
                </div>
            </div>
        </div>
    </div>

    <div id="largeImageOverlay" class="fixed inset-0 z-[60] hidden bg-black/95 flex items-center justify-center p-4 cursor-zoom-out" onclick="this.classList.add('hidden')">
        <img id="largeImg" src="" class="max-w-full max-h-full object-contain shadow-2xl transition-transform duration-300">
    </div>

    <script>
    function viewBookingDetails(id) {
        fetch(`/admin/bookings/${id}/json`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('m-id').innerText = '#' + data.id;
                document.getElementById('m-customer').innerText = data.customer;
                document.getElementById('m-vehicle').innerText = data.vehicle;
                document.getElementById('m-total').innerText = data.total;
                document.getElementById('m-processed-by').innerText = data.processed_by;
                document.getElementById('m-processed-at').innerText = data.processed_at;
                document.getElementById('m-img').src = data.payment_proof;
                
                const st = document.getElementById('m-status');
                st.innerText = data.status;
                st.className = `px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-widest shadow-sm `;
                
                if(data.status === 'Approved' || data.status === 'Completed') {
                    st.classList.add('bg-green-50', 'text-green-600', 'border-green-100');
                } else if(data.status === 'Rejected') {
                    st.classList.add('bg-red-50', 'text-red-600', 'border-red-100');
                } else {
                    st.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-100');
                }
                
                document.getElementById('bookingModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden'; 
            });
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openLargeImage(src) { 
        document.getElementById('largeImg').src = src;
        document.getElementById('largeImageOverlay').classList.remove('hidden');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            document.getElementById('largeImageOverlay').classList.add('hidden');
            closeBookingModal();
        }
    });
    </script>
@endsection