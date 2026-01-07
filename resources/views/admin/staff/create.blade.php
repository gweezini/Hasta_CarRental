@extends('layouts.admin')

@section('header_title', 'Register New Staff')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.staff.index') }}" class="text-gray-500 hover:text-gray-800 transition flex items-center gap-2">
            <i class="ri-arrow-left-line"></i> Back to Payroll
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-2xl font-black text-gray-800 mb-6">Staff Registration Form</h2>

        @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.staff.store') }}" method="POST">
            @csrf
            
            <!-- Personal Info -->
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition" required oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Matric / Staff ID</label>
                    <input type="text" name="matric_staff_id" value="{{ old('matric_staff_id') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">NRIC / Passport</label>
                    <input type="text" name="nric_passport" value="{{ old('nric_passport') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition" required oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition" required placeholder="+60123456789" oninput="this.value = this.value.replace(/[^0-9+]/g, ''); if(!this.value.startsWith('+')) this.value = '+' + this.value.replace(/\+/g, '');">
                </div>
            </div>

            <!-- Employment Details -->
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Employment Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Role</label>
                    <select name="role" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition">
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Staff)</option>
                        <option value="topmanagement" {{ old('role') == 'topmanagement' ? 'selected' : '' }}>Top Management</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Monthly Salary (RM)</label>
                    <input type="number" step="0.01" name="salary" value="{{ old('salary') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition" required>
                </div>
            </div>

            <!-- Security -->
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Account Security</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Password</label>
                    <input type="password" name="password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition" required>
                </div>
            </div>

            <!-- Banking Info -->
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Banking Details (Optional)</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Account Number</label>
                    <input type="text" name="account_number" value="{{ old('account_number') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Account Holder</label>
                    <input type="text" name="account_holder" value="{{ old('account_holder') }}" placeholder="Same as Full Name" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.staff.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                    Register Staff
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
