<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-start">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                &larr; Back to Dashboard
            </a>
        </div>
        <br>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2><br>
        <div class="hidden sm:flex sm:items-center sm:ml-10">
        
            <img 
                class="h-10 w-10 rounded-full object-cover border-2 border-indigo-50" 
                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4F46E5&color=fff" 
                alt="Profile Photo"
            >

            <div class="flex flex-col leading-none" style="margin-left:10px">
            
                <span class="text-sm font-bold text-gray-800 mb-1">
                    {{ Auth::user()->name }}
                </span>
            
                <span class="text-[10px] text-gray-500 mb-0.5">
                    {{ Auth::user()->email }}
                </span>

                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-1.5 rounded-md w-fit">
                    ID: {{ Auth::user()->matric_staff_id ?? 'Not Set' }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div>
                
                <div class="bg-gray-200 p-1 rounded-lg inline-flex items-center mb-0 overflow-x-auto max-w-full">
                    
                    <button onclick="openTab('booking')" id="tab-booking" class="px-6 py-2 text-sm font-bold rounded-md shadow bg-white text-gray-900 transition-all whitespace-nowrap">
                        Booking History
                    </button>

                    <button onclick="openTab('rewards')" id="tab-rewards" class="px-6 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition-all whitespace-nowrap">
                        My Rewards
                    </button>

                    <button onclick="openTab('personal')" id="tab-personal" class="px-6 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition-all whitespace-nowrap">
                        Personal Information
                    </button>
                    
                </div>

                <div id="content-booking" class="mt-4">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">My Past Bookings</h2>
                        
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto text-gray-400" style="width: 48px; height: 48px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Your rental history will appear here later.</p>
                        </div>

                    </div>
                </div>

                <div id="content-rewards" class="hidden mt-4">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">My Rewards & Loyalty Stamps</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 text-center">
                                <h3 class="text-indigo-600 font-bold text-lg">Total Loyalty Stamps</h3>
                                <p class="text-4xl font-extrabold text-gray-900 mt-2">0</p>
                                <p class="text-xs text-gray-500 mt-1">Earn stamps with booking!</p>
                            </div>

                            <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-100 text-center">
                                <h3 class="text-yellow-600 font-bold text-lg">Active Vouchers</h3>
                                <p class="text-xl font-bold text-gray-900 mt-4">-</p>
                            </div>
                        </div>

                        <hr class="border-gray-200 mb-8">

                        <div class="max-w-xl mx-auto text-center mt-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Have a Referral or Promo Code?</h3>
                            <p class="text-sm text-gray-500 mb-6">Enter your code below to claim exclusive discounts.</p>
                            
                            <form action="#" method="POST" class="flex justify-center items-center gap-3">
                            @csrf
                                <input type="text" name="promo_code" placeholder="ENTER CODE (E.G. WELCOME50)" class="w-64 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm uppercase">
                                <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Redeem
                                </button>
                            </form>
                            
                            <p class="text-xs text-gray-400 mt-4">* Terms and conditions apply.</p>
                        </div>
                    </div>
                </div>

                <div id="content-personal" class="space-y-6 mt-4 hidden">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        function openTab(tabName) {
            // Get Content Divs
            const contentBooking = document.getElementById('content-booking');
            const contentRewards = document.getElementById('content-rewards');
            const contentPersonal = document.getElementById('content-personal');
            
            // Get Buttons
            const btnBooking = document.getElementById('tab-booking');
            const btnRewards = document.getElementById('tab-rewards');
            const btnPersonal = document.getElementById('tab-personal');

            // Styles
            const activeClass = "px-6 py-2 text-sm font-bold rounded-md shadow bg-white text-gray-900 transition-all whitespace-nowrap";
            const inactiveClass = "px-6 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition-all whitespace-nowrap";

            // Hide Everything First
            contentBooking.classList.add('hidden');
            contentRewards.classList.add('hidden');
            contentPersonal.classList.add('hidden');

            // Reset All Buttons
            btnBooking.className = inactiveClass;
            btnRewards.className = inactiveClass;
            btnPersonal.className = inactiveClass;

            // Show Selected
            if (tabName === 'booking') {
                contentBooking.classList.remove('hidden');
                btnBooking.className = activeClass;
            } else if (tabName === 'rewards') {
                contentRewards.classList.remove('hidden');
                btnRewards.className = activeClass;
            } else if (tabName === 'personal') {
                contentPersonal.classList.remove('hidden');
                btnPersonal.className = activeClass;
            }
        }

        // Logic to keep Personal tab open after saving
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('status') === 'profile-updated' || $errors->any())
                openTab('personal');
            @endif
        });
    </script>
</x-app-layout>