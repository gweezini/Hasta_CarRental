@extends('layouts.admin')

@section('header_title', 'Staff Profile: ' . $staff->name)

@section('content')
<div class="mb-6 text-left">
    <a href="{{ route('admin.staff.index') }}" class="text-sm font-bold text-gray-500 hover:text-[#cd5c5c] transition flex items-center gap-2">
        <i class="ri-arrow-left-line"></i> Back to Payroll
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-left">
            <h3 class="text-xl font-black text-gray-800 mb-8 tracking-tight">Personal Information</h3>
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Full Name</label>
                    <p class="font-bold text-gray-900 text-lg">{{ $staff->name }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Position</label>
                    <span class="px-3 py-1 rounded-full text-[10px] font-black border border-blue-100 bg-blue-50 text-blue-600 uppercase tracking-widest">
                        {{ $staff->role }}
                    </span>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Email Address</label>
                    <p class="font-bold text-gray-700">{{ $staff->email }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Phone Number</label>
                    <p class="font-bold text-gray-700">{{ $staff->phone_number ?? $staff->phone ?? '---' }}</p>
                </div>
            </div>
        </div>

        {{-- Payroll History --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-left">
            <h3 class="text-xl font-black text-gray-800 mb-6 tracking-tight flex items-center gap-2">
                <i class="ri-history-line text-[#cb5c55]"></i> Payroll History
            </h3>
            <div class="space-y-4">
                @foreach($payrollMonths as $month)
                    @php
                        $claims = $monthlyClaims[$month] ?? collect([]);
                        $claimTotal = $claims->sum('amount');
                        $totalPayout = $staff->salary + $claimTotal;
                    @endphp
                    <div x-data="{ open: false }" class="border border-gray-100 rounded-2xl overflow-hidden transition-all duration-200" :class="{'shadow-md border-gray-200': open}">
                        {{-- Header / Summary Row --}}
                        <div @click="open = !open" class="bg-gray-50 p-5 flex justify-between items-center cursor-pointer hover:bg-gray-100 transition select-none">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-500 shadow-sm transition" :class="{'bg-[#cb5c55] text-white': open}">
                                    <i class="ri-calendar-event-line"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-base leading-none mb-1">{{ $month }}</h4>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $claims->count() }} Claims</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-black text-gray-900">RM {{ number_format($totalPayout, 2) }}</p>
                                <p class="text-[9px] text-green-500 font-bold uppercase tracking-widest flex items-center justify-end gap-1">
                                    <i class="ri-checkbox-circle-fill"></i> Paid
                                </p>
                            </div>
                        </div>

                        {{-- Expanded Details --}}
                        <div x-show="open" x-collapse class="bg-white border-t border-gray-100">
                            <div class="p-5 space-y-3">
                                {{-- Base Salary --}}
                                <div class="flex justify-between items-center py-2 border-b border-dashed border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-500 flex items-center justify-center text-xs">
                                            <i class="ri-briefcase-line"></i>
                                        </div>
                                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wide">Basic Salary</span>
                                    </div>
                                    <span class="font-bold text-gray-800">RM {{ number_format($staff->salary, 2) }}</span>
                                </div>

                                {{-- Claims List --}}
                                @foreach($claims as $claim)
                                <div class="flex justify-between items-center py-2 border-b border-dashed border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-6 h-6 rounded-md bg-orange-50 text-orange-500 flex items-center justify-center text-xs">
                                            <i class="ri-bill-line"></i>
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-gray-600 uppercase tracking-wide block">{{ $claim->claim_type }}</span>
                                            <span class="text-[9px] text-gray-400 font-mono">{{ $claim->claim_date_time->format('d M h:i A') }}</span>
                                        </div>
                                    </div>
                                    <span class="font-bold text-gray-600">+ RM {{ number_format($claim->amount, 2) }}</span>
                                </div>
                                @endforeach

                                {{-- Total Footer --}}
                                <div class="flex justify-between items-center pt-4 mt-2">
                                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Payout</span>
                                    <span class="text-xl font-black text-[#cb5c55]">RM {{ number_format($totalPayout, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-left border-t-4 border-[#cb5c55]">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="ri-bank-card-line text-[#cb5c55]"></i> Banking Details
            </h3>
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-xl">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Bank Name</label>
                    <p class="font-black text-gray-800 uppercase">{{ $staff->bank_name ?? 'Not Provided' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Account Number</label>
                    <p class="font-mono font-bold text-gray-800">{{ $staff->account_number ?? '---' }}</p>
                </div>
                <div class="pt-4 border-t border-dashed">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Monthly Basic Salary</label>
                    <p class="text-3xl font-black text-[#cb5c55]">RM {{ number_format($staff->salary, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
