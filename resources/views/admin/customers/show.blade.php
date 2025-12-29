<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Customer Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="bg-gray-50 border-b border-gray-200 flex justify-between items-center" style="padding: 25px;">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 uppercase">{{ $customer->name }}</h1>
                        <p class="text-sm text-gray-500 font-bold mt-1 tracking-wide">{{ $customer->email }} • Joined {{ $customer->created_at->format('Y') }}</p>
                    </div>
                    
                    <span class="px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider bg-blue-100 text-blue-700">
                        Customer
                    </span>
                </div>

                <div style="padding-left:50px; padding-top:10px; padding-bottom:20px;">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-10"><br> 
                        <div class="sm:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-2" style="font-size: 25px;">Personal Information</h3>
                            
                            <div class="grid grid-cols-2 gap-y-6 gap-x-8">
                                <div class="sm:col-span-1 flex flex-col gap-6">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=2563eb&color=fff&size=512" 
                                     alt="{{ $customer->name }}"
                                     class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg"
                                     style="box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.1);">
                                </div>
                                <br>
                                
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Full Name</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $customer->name }}</span>
                                </div>
                                <br>

                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Matric/Staff ID</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $customer->matric_staff_id }}</span>
                                </div>
                                <br>

                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">NRIC/Passport No.</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $customer->nric_passport }}</span>
                                </div>
                                <br>

                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $customer->email }}</span>
                                </div>
                                <br>

                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Phone Number</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $customer->phone_number }}</span>
                                </div>
                                <br>

                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Driving License No.</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $customer->driving_license }}</span>
                                </div>
                                <br>

                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Home Address</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $customer->address }}</span>
                                </div>
                                <br>

                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">College</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $customer->college->name }}</span>
                                </div>
                                <br>

                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Faculty</span>
                                    <span class="text-base font-semibold text-gray-800">{{ $customer->faculty->name }}</span>
                                </div>
                                <br>

                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Blacklist Status</span>
                                    @if($customer->is_blacklist == 1)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700" style="color: red">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            BLACKLISTED
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700" style="color: green">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Good Standing
                                        </span>
                                    @endif
                                </div>
                                <br>

                                <div class="col-span-2 mt-6 border-t pt-6">
                                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Uploaded Documents</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                        <span class="block text-xs font-bold text-gray-400 uppercase mb-3">Driving License</span>
                                        @if($customer->driving_license_path)
                                        <a href="{{ asset('storage/' . $customer->driving_license_path) }}" target="_blank"
                                           class="w-48 text-center bg-blue-600 text-white hover:bg-blue-700 font-bold py-2 px-4 rounded text-sm shadow-md transition transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                                           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                               View Document
                                        </a>
                                        @else
                                        <div class="w-48 py-2 bg-gray-200 rounded text-center text-gray-500 font-bold text-sm">
                                            Not Uploaded
                                        </div>
                                        @endif
                                        
                                        <span class="block text-xs font-bold text-gray-400 uppercase mb-3">Matric Card</span>
                                        @if($customer->matric_card_path)
                                        <a href="{{ asset('storage/' . $customer->matric_card_path) }}" target="_blank"
                                           class="w-48 text-center bg-blue-600 text-white hover:bg-blue-700 font-bold py-2 px-4 rounded text-sm shadow-md transition transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                                           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                           View Document
                                        </a>
                                        @else
                                        <div class="w-48 py-2 bg-gray-200 rounded text-center text-gray-500 font-bold text-sm">
                                            Not Uploaded
                                        </div>
                                        @endif

                                        <span class="block text-xs font-bold text-gray-400 uppercase mb-3">NRIC/Passport</span>
                                        @if($customer->nric_passport_path)
                                        <a href="{{ asset('storage/' . $customer->nric_passport_path) }}" target="_blank"
                                           class="w-48 text-center bg-blue-600 text-white hover:bg-blue-700 font-bold py-2 px-4 rounded text-sm shadow-md transition transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                                           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                               View Document
                                        </a>
                                        @else
                                        <div class="w-48 py-2 bg-gray-200 rounded text-center text-gray-500 font-bold text-sm">
                                            Not Uploaded
                                        </div>
                                        @endif
                                    </div>
                                </div>  
                            </div>
                            <br>

                            <div class="mt-10 flex justify-end pt-6 border-t border-gray-100">
                                <a href="{{ route('admin.customers.index') }}" style="margin-right: 20px; padding: 12px 40px; font-size: 16px;"
                                   class="bg-white border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                                   Back
                                </a>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>
        </div>
    </div>
</x-app-layout>