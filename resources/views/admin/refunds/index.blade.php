@extends('layouts.admin')

@section('header_title', 'Deposit Refunds')

@section('content')
    <div class="mb-6 flex gap-4">
        <a href="{{ route('admin.refunds.index', ['status' => 'Pending']) }}" 
           class="px-6 py-2 rounded-full font-bold text-sm transition {{ $status === 'Pending' ? 'bg-[#cb5c55] text-white shadow-lg' : 'bg-white text-gray-500 hover:bg-gray-50 border border-gray-100' }}">
            Pending Refunds
        </a>
        <a href="{{ route('admin.refunds.index', ['status' => 'Returned']) }}" 
           class="px-6 py-2 rounded-full font-bold text-sm transition {{ $status === 'Returned' ? 'bg-green-600 text-white shadow-lg' : 'bg-white text-gray-500 hover:bg-gray-50 border border-gray-100' }}">
            Returned History
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/30 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 tracking-tight">
                {{ $status === 'Pending' ? 'Refunds to Process' : 'Completed Refunds' }}
            </h3>
            <span class="text-xs font-bold bg-gray-200 text-gray-600 px-3 py-1 rounded-full">{{ $refunds->total() }} records</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-[10px] uppercase text-gray-400 font-black tracking-widest border-b">
                    <tr>
                        <th class="px-6 py-4">Booking</th>
                        <th class="px-6 py-4">Customer & Bank</th>
                        <th class="px-6 py-4">Vehicle</th>
                        <th class="px-6 py-4">Return Date</th>
                        <th class="px-6 py-4 text-right">Deposit Amount</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($refunds as $booking)
                        <tr class="hover:bg-gray-50 transition cursor-pointer group" onclick="window.location='{{ route('admin.bookings.show_detail', $booking->id) }}'">
                            <td class="px-6 py-5">
                                <span class="font-bold text-gray-800 group-hover:text-[#cb5c55] transition">
                                    #{{ $booking->id }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="font-bold text-gray-900">{{ $booking->customer_name }}</div>
                                <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                                    <p><span class="font-bold text-[10px] uppercase text-gray-400">Bank:</span> {{ $booking->refund_bank_name ?? 'N/A' }}</p>
                                    <p><span class="font-bold text-[10px] uppercase text-gray-400">Acc:</span> {{ $booking->refund_account_number ?? 'N/A' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="font-bold text-gray-800">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</div>
                                <div class="text-xs font-mono text-gray-500">{{ $booking->vehicle->plate_number }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->return_date_time)->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($booking->return_date_time)->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-5 text-right font-black text-gray-700 text-base">
                                RM {{ number_format($booking->deposit_amount, 2) }}
                            </td>
                            <td class="px-6 py-5 text-center" onclick="event.stopPropagation()">
                                @if($status === 'Pending')
                                    <button type="button" onclick="event.stopPropagation(); openRefundModal('{{ $booking->id }}', '{{ number_format($booking->deposit_amount, 2) }}', '{{ $booking->customer_name }}')" 
                                            class="bg-[#cb5c55] text-white px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-[#b04a45] shadow-sm transition z-10 relative">
                                        Return Deposit
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-1 text-green-600 font-bold text-xs uppercase tracking-widest bg-green-50 px-3 py-1 rounded-full border border-green-100">
                                        <i class="ri-checkbox-circle-fill"></i> Returned
                                    </span>
                                    <div class="text-[10px] text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($booking->deposit_returned_at)->format('d M Y') }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="ri-hand-coin-line text-4xl mb-3 block opacity-50"></i>
                                <p class="font-bold uppercase tracking-widest text-xs">No refunds found in this category.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100">
            {{ $refunds->appends(['status' => $status])->links() }}
        </div>
    </div>

    <!-- Refund Modal -->
    <div id="refundModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeRefundModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <form id="refundForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="ri-refund-2-line text-blue-600 text-lg"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Process Deposit Return</h3>
                                <div class="mt-2 text-sm text-gray-500">
                                    <p class="mb-4">You are about to mark the deposit for <strong id="m-customer" class="text-gray-800"></strong> as returned.</p>
                                    
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mb-4 text-center">
                                        <span class="text-xs uppercase font-bold text-gray-400 block mb-1">Refund Amount</span>
                                        <span class="text-2xl font-black text-gray-800">RM <span id="m-amount"></span></span>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Upload Transfer Receipt</label>
                                        <input type="file" name="deposit_receipt" required accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition border border-gray-200 rounded-lg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-xs uppercase tracking-widest">
                            Confirm Return
                        </button>
                        <button type="button" onclick="closeRefundModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-xs uppercase tracking-widest">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openRefundModal(id, amount, customer) {
        document.getElementById('m-customer').innerText = customer;
        document.getElementById('m-amount').innerText = amount;
        
        // Dynamic route construction (assuming route is named 'admin.bookings.return_deposit')
        // Using JS string interpolation for the ID.
        // Base route without ID:
        const form = document.getElementById('refundForm');
        form.action = `/admin/bookings/${id}/return-deposit`; // Corrected to plural 'bookings' to match web.php
        
        document.getElementById('refundModal').classList.remove('hidden');
    }

    function closeRefundModal() {
        document.getElementById('refundModal').classList.add('hidden');
    }
    </script>
@endsection
