@extends('layouts.admin')

@section('header_title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center">
                    <div class="w-24 h-24 bg-red-50 text-[#cb5c55] rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-md">
                        <i class="ri-user-settings-line text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">{{ $admin->name }}</h3>
                    <p class="text-gray-400 text-xs uppercase font-bold tracking-widest mt-1">
                        {{ $admin->role == 'topmanagement' ? 'Top Management' : $admin->role }}
                    </p>
                </div>
            </div>

            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-[#cb5c55] rounded-full"></span> Account Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase">Full Name</label>
                            <input type="text" name="name" value="{{ $admin->name }}" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-[#cb5c55]/20 transition">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase">Email</label>
                            <input type="email" name="email" value="{{ $admin->email }}" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-[#cb5c55]/20 transition">
                        </div>
                    </div>
                </div>

                {{-- Payroll History --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-green-500 rounded-full"></span> Payroll History
                    </h4>
                    <div class="space-y-4">
                        @foreach($payrollMonths as $month)
                            @php
                                $claims = $monthlyClaims[$month] ?? collect([]);
                                $claimTotal = $claims->sum('amount');
                                $totalPayout = $admin->salary + $claimTotal;
                            @endphp
                            <div x-data="{ open: false }" class="border border-gray-100 rounded-2xl overflow-hidden transition-all duration-200" :class="{'shadow-md border-gray-200': open}">
                                <div @click="open = !open" class="bg-gray-50 p-5 flex justify-between items-center cursor-pointer hover:bg-gray-100 transition select-none">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-500 shadow-sm transition" :class="{'bg-green-500 text-white': open}">
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
                                            <span class="font-bold text-gray-800">RM {{ number_format($admin->salary, 2) }}</span>
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
                                            <span class="text-xl font-black text-green-500">RM {{ number_format($totalPayout, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 border-t-4 border-t-yellow-400">
                    <h4 class="text-lg font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <i class="ri-lock-password-line text-yellow-500"></i> Change Password
                    </h4>
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-6 italic">Leave blank if you don't want to change</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase">New Password</label>
                            <input type="password" name="password" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-[#cb5c55]/20 transition">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-[#cb5c55]/20 transition">
                        </div>
                    </div>
                </div>

                @if(Auth::user()->isAdmin())
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-blue-500 rounded-full"></span> Finance & Payroll
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase">Staff ID</label>
                            <input type="text" name="staff_id" value="{{ $admin->matric_staff_id }}" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase">Monthly Salary</label>
                            <input type="number" step="0.01" name="salary" value="{{ $admin->salary }}" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase">Bank Name</label>
                            <input type="text" name="bank_name" value="{{ $admin->bank_name }}" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700">
                        </div>
                        <div class="space-y-2 text-blue-600">
                            <label class="text-xs font-black uppercase">Account Holder</label>
                            <input type="text" name="account_holder" value="{{ $admin->account_holder }}" required class="w-full bg-blue-50/50 border-none rounded-xl px-4 py-3 text-gray-700">
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-black text-gray-400 uppercase">Account Number</label>
                            <input type="text" name="account_number" value="{{ $admin->account_number }}" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700">
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-black transition shadow-lg">Save All Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection