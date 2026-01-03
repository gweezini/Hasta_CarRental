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
                    <p class="text-gray-400 text-xs uppercase font-bold tracking-widest mt-1">Administrator</p>
                    
                    <div class="mt-6 pt-6 border-t border-gray-50 text-left">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                                <i class="ri-id-card-line text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Staff ID</p>
                            
                                <p class="text-sm font-bold text-gray-700">{{ $admin->matric_staff_id ?? 'Not Set' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-6">
                
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-[#cb5c55] rounded-full"></span>
                        Personal Information
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase ml-1">Full Name</label>
                            <input type="text" name="name" value="{{ $admin->name }}" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-[#cb5c55]/20 transition">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase ml-1">Email Address</label>
                            <input type="email" name="email" value="{{ $admin->email }}" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-[#cb5c55]/20 transition">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                        Finance & Payroll
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase ml-1">Staff ID</label>
                            <input type="text" name="staff_id" value="{{ $admin->matric_staff_id }}" placeholder="e.g. STF-001" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-[#cb5c55]/20 transition">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase ml-1">Monthly Salary (RM)</label>
                            <input type="number" step="0.01" name="salary" value="{{ $admin->salary }}" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-[#cb5c55]/20 transition">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase ml-1">Bank Name</label>
                            <input type="text" name="bank_name" value="{{ $admin->bank_name }}" placeholder="e.g. Maybank" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-[#cb5c55]/20 transition">
                        </div>
                       
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase ml-1">Account Holder Name</label>
                            <input type="text" name="account_holder" value="{{ $admin->account_holder }}" placeholder="Full Name as per bank" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-[#cb5c55]/20 transition">
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-black text-gray-400 uppercase ml-1">Account Number</label>
                            <input type="text" name="account_number" value="{{ $admin->account_number }}" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-[#cb5c55]/20 transition">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-black transition shadow-lg flex items-center gap-2">
                        <i class="ri-save-line"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection