@extends('layouts.admin')

@section('header_title')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.customers.index') }}" class="text-gray-400 hover:text-[#cb5c55] transition" title="Back to Customers">
            <i class="ri-arrow-left-line text-xl align-middle"></i>
        </a>
        <span class="align-middle">Customer Profile</span>
    </div>
@endsection

@section('content')
<div x-data="{ showBlacklistModal: false }">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50 border-b border-gray-100 p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                
                <div class="flex gap-6 items-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=cb5c55&color=fff&size=128" 
                         alt="{{ $customer->name }}"
                         class="w-24 h-24 rounded-full border-4 border-white shadow-md">
                    
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">{{ $customer->name }}</h1>
                        <div class="flex items-center gap-4 mt-2 text-gray-500 text-sm">
                            <span><i class="ri-mail-line mr-1"></i> {{ $customer->email }}</span>
                            <span><i class="ri-calendar-line mr-1"></i> Joined {{ $customer->created_at->format('M Y') }}</span>
                        </div>
                        
                        <div class="mt-3">
                            @if($customer->is_blacklisted)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                    BLACKLISTED
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                    ACTIVE
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    @if($customer->is_blacklisted)
                        <form action="{{ route('admin.customers.blacklist', $customer->id) }}" method="POST" onsubmit="return confirm('Confirm to whitelist this user?')">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-xl shadow-lg shadow-green-200 text-base font-bold tracking-wide transform hover:scale-105 transition-all">
                                Whitelist User
                            </button>
                        </form>
                    @else
                        <button @click="showBlacklistModal = true" class="bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-xl shadow-lg shadow-red-200 text-base font-bold tracking-wide transform hover:scale-105 transition-all">
                            Blacklist User
                        </button>
                    @endif
                </div>

            </div>
        </div>

        @if($customer->is_blacklisted)
        <div class="mx-8 mt-8 bg-red-50 border-l-4 border-red-500 p-5 rounded-r-lg shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0 mt-0.5">
                    <i class="ri-alarm-warning-fill text-red-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-base font-bold text-red-800 uppercase tracking-wide">Account Restricted</h3>
                    <p class="mt-1 text-sm text-red-700">
                        Reason for blacklisting: <strong class="text-red-900 bg-red-100 px-1 rounded">{{ $customer->blacklist_reason ?? 'No reason provided' }}</strong>
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="p-8">
            <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">Personal Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 gap-x-12">
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Matric / Staff ID</span>
                    <span class="text-base font-medium text-gray-800">{{ $customer->matric_staff_id }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">NRIC / Passport</span>
                    <span class="text-base font-medium text-gray-800">{{ $customer->nric_passport }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Phone Number</span>
                    <span class="text-base font-medium text-gray-800 font-mono">{{ $customer->phone_number ?? $customer->phone ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">College</span>
                    <span class="text-base font-medium text-gray-800">{{ $customer->college ? $customer->college->name : '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Faculty</span>
                    <span class="text-base font-medium text-gray-800">{{ $customer->faculty ? $customer->faculty->name : '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Driving License</span>
                    <span class="text-base font-medium text-gray-800">{{ $customer->driving_license }}</span>
                </div>
                <div class="col-span-1 md:col-span-3">
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Home Address</span>
                    <span class="text-base font-medium text-gray-800">{{ $customer->address }}</span>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Uploaded Documents</h3>
                <div class="flex flex-wrap gap-4">
                    @if($customer->driving_license_path)
                        <a href="{{ asset('storage/' . $customer->driving_license_path) }}" target="_blank" class="flex items-center gap-2 px-5 py-3 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition border border-blue-200 font-bold text-sm">
                            <i class="ri-file-user-line"></i> Driving License
                        </a>
                    @else
                        <span class="px-5 py-3 bg-gray-50 text-gray-400 rounded-lg border border-gray-200 text-sm font-medium">No License</span>
                    @endif

                    @if($customer->matric_card_path)
                        <a href="{{ asset('storage/' . $customer->matric_card_path) }}" target="_blank" class="flex items-center gap-2 px-5 py-3 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition border border-purple-200 font-bold text-sm">
                            <i class="ri-id-card-line"></i> Matric Card
                        </a>
                    @else
                        <span class="px-5 py-3 bg-gray-50 text-gray-400 rounded-lg border border-gray-200 text-sm font-medium">No Matric Card</span>
                    @endif

                    @if($customer->nric_passport_path)
                        <a href="{{ asset('storage/' . $customer->nric_passport_path) }}" target="_blank" class="flex items-center gap-2 px-5 py-3 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition border border-green-200 font-bold text-sm">
                            <i class="ri-passport-line"></i> NRIC/Passport
                        </a>
                    @else
                        <span class="px-5 py-3 bg-gray-50 text-gray-400 rounded-lg border border-gray-200 text-sm font-medium">No NRIC/Passport</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div x-show="showBlacklistModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div @click.away="showBlacklistModal = false" class="bg-white rounded-xl shadow-2xl w-96 p-6 text-left transform transition-all scale-100 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Blacklist User</h3>
            <p class="text-xs text-gray-500 mb-4">Please provide a reason for blacklisting <strong>{{ $customer->name }}</strong>.</p>
            
            <form action="{{ route('admin.customers.blacklist', $customer->id) }}" method="POST">
                @csrf
                <textarea name="blacklist_reason" rows="3" required placeholder="Reason (e.g. Damaged vehicle...)" class="w-full text-sm border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 mb-4 p-3 bg-gray-50 border outline-none resize-none"></textarea>
                
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showBlacklistModal = false" class="px-5 py-2.5 text-xs font-bold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-red-500 rounded-lg hover:bg-red-600 transition shadow-lg">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection