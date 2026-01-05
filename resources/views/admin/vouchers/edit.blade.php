@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Voucher</h2>
            <p class="text-gray-500 text-sm mt-1">Update the details for voucher: <span class="font-mono bg-gray-100 px-2 py-1 rounded text-[#cb5c55]">{{ $voucher->code }}</span></p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="ri-error-warning-line text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                {{-- Code --}}
                <div class="col-span-2 md:col-span-1">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Voucher Code <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="code" id="code" value="{{ old('code', $voucher->code) }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2 px-3 border" 
                            required>
                    </div>
                </div>

                {{-- Name --}}
                <div class="col-span-2 md:col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Voucher Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $voucher->name) }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2 px-3 border" 
                        required>
                </div>

                {{-- Type --}}
                <div class="col-span-2 md:col-span-1">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Discount Type <span class="text-red-500">*</span></label>
                    <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2 px-3 border bg-white">
                        <option value="fixed" {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (RM)</option>
                        <option value="percent" {{ old('type', $voucher->type) == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                    </select>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Discount Value <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="value" id="value" value="{{ old('value', $voucher->value) }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2 px-3 border" 
                        required>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label for="uses_remaining" class="block text-sm font-medium text-gray-700 mb-1">Uses Remaining</label>
                    <input type="number" name="uses_remaining" id="uses_remaining" value="{{ old('uses_remaining', $voucher->uses_remaining) }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#cb5c55] focus:ring-[#cb5c55] py-2 px-3 border" 
                        placeholder="Leave blank for unlimited">
                </div>

                <div class="col-span-2 md:col-span-1 flex flex-col justify-end gap-3 pb-1">
                    
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="single_use" name="single_use" type="checkbox" value="1" {{ old('single_use', $voucher->single_use) ? 'checked' : '' }} 
                                class="focus:ring-[#cb5c55] h-4 w-4 text-[#cb5c55] border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="single_use" class="font-medium text-gray-700">Single Use Per User</label>
                        </div>
                    </div>

                    {{-- Active --}}
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }} 
                                class="focus:ring-[#cb5c55] h-4 w-4 text-[#cb5c55] border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-gray-700">Active Status</label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-end gap-4 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.vouchers.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Cancel</a>
                <button type="submit" class="bg-[#cb5c55] text-white px-6 py-2 rounded shadow hover:bg-[#b04b45] transition font-medium">
                    Update Voucher
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
