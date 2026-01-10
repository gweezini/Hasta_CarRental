@extends('layouts.admin')

@section('header_title', 'Create Voucher')

@section('content')
<div class="mb-6 text-left">
    <a href="{{ route('admin.vouchers.index') }}" class="text-sm font-bold text-gray-400 hover:text-[#cb5c55] transition flex items-center gap-1.5 group">
        <i class="ri-arrow-left-line group-hover:-translate-x-1 transition-transform"></i> Back to Vouchers
    </a>
</div>

<div class="max-w-4xl mx-auto pb-12">
    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        {{-- Form Header --}}
        <div class="px-10 py-8 border-b border-gray-50 bg-gray-50/30">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Voucher Details</h2>
            <p class="text-gray-400 text-sm font-medium mt-1">Configure the rewards and restrictions for this code.</p>
        </div>

        <form action="{{ route('admin.vouchers.store') }}" method="POST" class="p-10 text-left">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                
                {{-- Code --}}
                <div class="col-span-2 md:col-span-1 space-y-2">
                    <label for="code" class="block text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Voucher Code <span class="text-[#cb5c55]">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#cb5c55] transition-colors">
                            <i class="ri-key-2-line text-lg"></i>
                        </div>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" 
                            class="w-full rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:border-[#cb5c55] focus:ring-0 py-3.5 pl-11 pr-4 font-black text-gray-800 placeholder-gray-300 transition-all uppercase" 
                            placeholder="e.g. HASTA10" required>
                    </div>
                </div>

                {{-- Name --}}
                <div class="col-span-2 md:col-span-1 space-y-2">
                    <label for="name" class="block text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Display Name <span class="text-[#cb5c55]">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                        class="w-full rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:border-[#cb5c55] focus:ring-0 py-3.5 px-5 font-bold text-gray-800 placeholder-gray-300 transition-all" 
                        placeholder="e.g. Chinese New Year Specials" required>
                </div>

                {{-- Type --}}
                <div class="col-span-2 md:col-span-1 space-y-2">
                    <label for="type" class="block text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Reward Type <span class="text-[#cb5c55]">*</span></label>
                    <div class="relative">
                        <select name="type" id="type" class="w-full rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:border-[#cb5c55] focus:ring-0 py-3.5 px-5 font-black text-gray-800 appearance-none cursor-pointer transition-all">
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed RM Discount</option>
                            <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percentage (%) Off</option>
                            <option value="free_hours" {{ old('type') == 'free_hours' ? 'selected' : '' }}>Free Hours Reward</option>
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                {{-- Value --}}
                <div class="col-span-2 md:col-span-1 space-y-2">
                    <label for="value" class="block text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Discount Value <span class="text-[#cb5c55]">*</span></label>
                    <div class="relative group">
                        <input type="number" step="0.01" name="value" id="value" value="{{ old('value') }}" 
                            class="w-full rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:border-[#cb5c55] focus:ring-0 py-3.5 px-5 font-black text-gray-800 placeholder-gray-300 transition-all" 
                            placeholder="e.g. 10.00" required>
                    </div>
                </div>

                {{-- Uses Remaining --}}
                <div class="col-span-2 md:col-span-1 space-y-2">
                    <label for="uses_remaining" class="block text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Global Usage Limit</label>
                    <input type="number" name="uses_remaining" id="uses_remaining" value="{{ old('uses_remaining') }}" 
                        class="w-full rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:border-[#cb5c55] focus:ring-0 py-3.5 px-5 font-bold text-gray-800 placeholder-gray-300 transition-all" 
                        placeholder="Unlimited (Leave Blank)">
                </div>

                {{-- Expiry Date --}}
                <div class="col-span-2 space-y-2" x-data="{ hasExpiry: false }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Expiry Date</label>
                        <div class="flex items-center gap-2 cursor-pointer" @click="hasExpiry = !hasExpiry">
                           <span class="text-[10px] font-black uppercase tracking-widest transition-colors" :class="hasExpiry ? 'text-[#cb5c55]' : 'text-gray-300'">Enabled</span>
                           <div :class="hasExpiry ? 'bg-[#cb5c55]' : 'bg-gray-200'" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out">
                               <span :class="hasExpiry ? 'translate-x-4' : 'translate-x-0'" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                           </div>
                        </div>
                    </div>
                    
                    <div x-show="hasExpiry" x-transition.opacity>
                        <input type="datetime-local" name="expires_at" id="expires_at" 
                               class="w-full rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:border-[#cb5c55] focus:ring-0 py-3.5 px-5 font-black text-gray-800 transition-all"
                               :required="hasExpiry">
                    </div>
                    <div x-show="!hasExpiry" class="bg-green-50/50 text-green-600 px-5 py-3.5 rounded-2xl border border-green-100/50 text-xs font-black uppercase tracking-widest italic flex items-center gap-2">
                        <i class="ri-checkbox-circle-line text-lg"></i> This voucher will never expire
                    </div>
                </div>

                <div class="col-span-2 pt-4">
                    <div class="bg-gray-50/50 rounded-3xl p-6 border-2 border-gray-100 border-dashed">
                        <div class="flex items-start">
                            <div class="flex items-center h-6">
                                <input id="single_use" name="single_use" type="checkbox" value="1" {{ old('single_use', '1') ? 'checked' : '' }} 
                                    class="h-5 w-5 text-[#cb5c55] border-gray-300 rounded-lg focus:ring-[#cb5c55] transition-all cursor-pointer">
                            </div>
                            <div class="ml-4">
                                <label for="single_use" class="block text-sm font-black text-gray-800 uppercase tracking-tight cursor-pointer">Single Use Per Customer</label>
                                <p class="text-xs text-gray-400 font-medium mt-1">Check this to prevent a single student/staff from using the same code multiple times.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-between gap-6 border-t border-gray-100 pt-10 mt-4">
                <a href="{{ route('admin.vouchers.index') }}" class="text-sm font-black text-gray-400 uppercase tracking-widest hover:text-gray-900 transition-colors">Discard Changes</a>
                <button type="submit" class="bg-gray-900 text-white px-10 py-4 rounded-2xl shadow-xl shadow-gray-200 hover:bg-[#cb5c55] hover:shadow-[#cb5c55]/20 transition-all font-black text-xs uppercase tracking-[0.2em] active:scale-95">
                    Generate Voucher
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
