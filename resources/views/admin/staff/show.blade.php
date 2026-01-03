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
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Monthly Salary</label>
                    <p class="text-3xl font-black text-[#cb5c55]">RM {{ number_format($staff->salary, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection