@extends('layouts.admin')

@section('header_title', 'Voucher Management')

@section('content')
    <div class="mb-8 text-left">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Voucher List</h2>
                <p class="text-gray-500 text-sm">Manage discount codes and track their usage</p>
            </div>
            <a href="{{ route('admin.vouchers.create') }}" class="px-5 py-2.5 bg-[#cb5c55] text-white rounded-2xl hover:bg-[#b04b45] transition shadow-lg shadow-red-100 font-black text-xs uppercase tracking-widest flex items-center gap-2">
                <i class="ri-add-line text-lg"></i> Create Voucher
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100 border border-gray-100 overflow-hidden mb-8">
        <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
            <h3 class="text-lg font-black text-gray-800 tracking-tight flex items-center gap-2">
                <i class="ri-coupon-3-line text-[#cb5c55]"></i> Registered Vouchers
            </h3>
            <span class="bg-[#cb5c55] text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-sm uppercase tracking-widest">
                Total: {{ $vouchers->count() }}
            </span>
        </div>
        
        <div class="overflow-x-auto text-left">
            <table class="w-full">
                <thead class="bg-gray-50/50 text-[10px] uppercase text-gray-400 font-black tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-8 py-4">Code</th>
                        <th class="px-8 py-4">Name</th>
                        <th class="px-8 py-4">Reward</th>
                        <th class="px-8 py-4">Uses Left</th>
                        <th class="px-8 py-4">Expiry</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($vouchers as $v)
                    @php
                        $isExpired = $v->expires_at && $v->expires_at->isPast();
                    @endphp
                    <tr onclick="window.location='{{ route('admin.vouchers.show', $v->id) }}'" 
                        class="hover:bg-gray-50/50 transition cursor-pointer {{ (!$v->is_active || $isExpired) ? 'opacity-50 grayscale-[0.8]' : '' }}">
                        
                        <td class="px-8 py-5">
                            <div class="inline-flex items-center gap-2 group">
                                <span class="font-black text-gray-900 group-hover:text-[#cb5c55] transition-colors decoration-dotted underline underline-offset-4 tracking-tight text-base whitespace-nowrap">{{ $v->code }}</span>
                                <i class="ri-history-line text-gray-300 group-hover:text-[#cb5c55] transition-colors"></i>
                            </div>
                        </td>

                        <td class="px-8 py-5">
                            <p class="text-sm font-bold text-gray-600 block line-clamp-1">{{ $v->name }}</p>
                        </td>

                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="text-base font-black text-[#cb5c55] tracking-tight">{{ $v->label }}</span>
                        </td>

                        <td class="px-8 py-5 text-sm font-black text-gray-700 whitespace-nowrap">
                            @if($v->uses_remaining === null)
                                <span class="text-xl opacity-30">∞</span>
                            @else
                                <span class="bg-gray-50 px-2 py-1 rounded-lg border border-gray-100">{{ $v->uses_remaining }}</span>
                            @endif
                        </td>

                        <td class="px-8 py-5 text-left whitespace-nowrap">
                            @if($v->expires_at)
                                @if($v->expires_at->isPast())
                                    <span class="px-2.5 py-1 inline-flex text-[10px] font-black uppercase tracking-wider rounded-lg bg-red-100 text-red-800 border border-red-200">
                                        Expired
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-gray-500 flex items-center gap-1.5">
                                        <i class="ri-calendar-event-line text-[#cb5c55] opacity-50"></i>
                                        {{ $v->expires_at->format('d M Y') }}
                                    </span>
                                @endif
                            @else
                                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest italic">Always Active</span>
                            @endif
                        </td>

                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="px-2.5 py-1 inline-flex text-[10px] font-black uppercase tracking-wider rounded-lg {{ $v->is_active && !$isExpired ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                                {{ $v->is_active && !$isExpired ? 'Active' : 'Missing' }}
                            </span>
                        </td>

                        <td class="px-8 py-5 text-center" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.vouchers.edit', $v->id) }}" class="px-3 py-1.5 bg-gray-900 text-white rounded-lg hover:bg-[#cb5c55] transition-all font-black text-[10px] uppercase tracking-wider">
                                    Edit
                                </a>
                                
                                <form action="{{ route('admin.vouchers.toggle', $v->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Change status of this voucher?');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg font-black text-[10px] uppercase tracking-wider transition-all {{ $v->is_active ? 'bg-red-50 text-red-600 hover:bg-red-600 hover:text-white' : 'bg-green-50 text-green-600 hover:bg-green-600 hover:text-white' }}">
                                        {{ $v->is_active ? 'Invalidate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="ri-coupon-3-line text-6xl text-gray-100"></i>
                                <p class="text-gray-400 font-bold italic text-lg">No vouchers found.</p>
                                <p class="text-gray-300 text-sm">Create your first discount code to start rewarding customers!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
