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
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-5 font-bold text-gray-500 text-xs">#{{ $booking->id }}</td>
                        
                        <td class="px-6 py-5">
                            {{-- 加大客户姓名 --}}
                            <a href="{{ route('admin.customers.show', $booking->user->id) }}" class="text-base font-bold text-gray-900 hover:text-[#cb5c55] hover:underline transition block leading-tight">
                                {{ $booking->user->name }}
                            </a>
                            {{-- 优化联系电话显示 --}}
                            <p class="text-xs text-gray-500 font-medium mt-1">
                                <i class="ri-phone-line mr-1"></i>{{ $booking->user->phone_number ?? $booking->user->phone ?? 'No Phone' }}
                            </p>
                        </td>

                        <td class="px-6 py-5">
                            <p class="font-bold text-gray-800 uppercase leading-tight">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                            <p class="text-xs text-gray-400 font-mono mt-0.5 tracking-wider">{{ $booking->vehicle->plate_number }}</p>
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded border border-green-100 w-fit">
                                    FROM: {{ \Carbon\Carbon::parse($booking->start_time ?? $booking->start_date)->format('d M, h:i A') }}
                                </span>
                                <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100 w-fit">
                                    TO: {{ \Carbon\Carbon::parse($booking->end_time ?? $booking->end_date)->format('d M, h:i A') }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-5 uppercase tracking-tighter">
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
                        </td>

                        <td class="px-6 py-5 text-center">
                            @if($booking->status == 'Waiting for Verification' || $booking->status == 'Verify Receipt')
                                <a href="{{ route('admin.payment.verify', $booking->id) }}" class="inline-block bg-[#cd5c5c] text-white text-[11px] font-black px-5 py-2 rounded-lg shadow hover:bg-[#b04a45] transition uppercase tracking-widest">Verify</a>
                            
                            @elseif($booking->status == 'Approved')
                                <form action="{{ route('admin.booking.return', $booking->id) }}" method="POST" onsubmit="return confirm('Confirm vehicle return?')">
                                    @csrf
                                    <button type="submit" class="bg-white text-green-700 border-2 border-green-500 hover:bg-green-500 hover:text-white font-black text-[10px] px-3 py-2 rounded-lg transition-all flex items-center gap-1 mx-auto uppercase shadow-sm">
                                        <i class="ri-checkbox-circle-line"></i> Confirm Return
                                    </button>
                                </form>
                            
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
@endsection