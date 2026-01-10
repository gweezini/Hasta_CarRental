@extends('layouts.admin')

@section('header_title', 'Voucher Redemption History')

@section('content')
<div class="mb-6 text-left">
    <a href="{{ route('admin.vouchers.index') }}" class="text-sm font-bold text-gray-400 hover:text-[#cb5c55] transition flex items-center gap-1.5 group">
        <i class="ri-arrow-left-line group-hover:-translate-x-1 transition-transform"></i> Back to Vouchers
    </a>
</div>

<div class="max-w-4xl mx-auto space-y-6">
    {{-- Voucher Summary Card --}}
    <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-gray-100 border border-gray-100 flex flex-col md:flex-row gap-8 items-center text-left relative overflow-hidden">
        {{-- Background Decoration --}}
        <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
            <i class="ri-coupon-3-line text-9xl -rotate-12"></i>
        </div>

        <div class="flex-1 space-y-4 text-center md:text-left z-10">
            <div class="space-y-1">
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <h2 class="text-4xl font-black text-gray-900 tracking-tighter">{{ $voucher->code }}</h2>
                    <span class="px-3 py-1 {{ $voucher->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} text-[10px] font-black uppercase tracking-widest rounded-full border border-current opacity-70 italic shadow-sm">
                        {{ $voucher->is_active ? 'Currently Active' : 'Invalidated' }}
                    </span>
                </div>
                <p class="text-xl font-bold text-gray-400 capitalize">{{ $voucher->name }} — <span class="text-[#cb5c55]">{{ $voucher->label }}</span></p>
            </div>

            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="inline-flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#cb5c55] transition-all shadow-lg shadow-gray-200 active:scale-95">
                    <i class="ri-edit-line"></i> Edit Voucher
                </a>
                
                <form action="{{ route('admin.vouchers.toggle', $voucher->id) }}" method="POST" onsubmit="return confirm('Change status of this voucher?');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl font-black text-xs uppercase tracking-widest transition-all border-2 {{ $voucher->is_active ? 'border-red-100 text-red-400 hover:bg-red-50' : 'border-green-100 text-green-400 hover:bg-green-50' }} active:scale-95">
                        <i class="{{ $voucher->is_active ? 'ri-close-circle-line' : 'ri-checkbox-circle-line' }}"></i>
                        {{ $voucher->is_active ? 'Invalidate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 shrink-0">
            <div class="bg-gray-50 p-4 rounded-2xl text-center border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Redeemed</p>
                <p class="text-2xl font-black text-gray-800">{{ $redeemers->count() }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-2xl text-center border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Uses Left</p>
                <p class="text-2xl font-black text-gray-800">{{ $voucher->uses_remaining ?? '∞' }}</p>
            </div>
        </div>
    </div>

    {{-- Redeemers List --}}
    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100 border border-gray-100 overflow-hidden text-left">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
            <h3 class="text-lg font-black text-gray-800 flex items-center gap-2">
                <i class="ri-group-line text-[#cb5c55]"></i> Redemption Log
            </h3>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Showing all activity</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Matric/Staff ID</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Used Date & Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($redeemers as $redeem)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 bg-[#cb5c55]/10 text-[#cb5c55] rounded-xl flex items-center justify-center font-black text-sm">
                                    {{ substr($redeem->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.customers.show', $redeem->user->id) }}" class="font-black text-gray-800 text-sm tracking-tight hover:text-[#cb5c55] transition-colors">
                                        {{ $redeem->user->name }}
                                    </a>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">{{ $redeem->user->role }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="font-bold text-gray-600 font-mono bg-gray-50 px-3 py-1 rounded-lg border border-gray-100 text-xs">
                                {{ $redeem->user_id }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-gray-500 font-bold text-sm">
                            <div class="flex items-center gap-2">
                                <i class="ri-calendar-check-line text-[#cb5c55]"></i>
                                {{ \Carbon\Carbon::parse($redeem->used_at)->format('d M Y, h:i A') }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="ri-coupon-3-line text-6xl text-gray-100"></i>
                                <p class="text-gray-400 font-bold italic">No redemptions recorded yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
