<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex justify-start">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    &larr; Back to Dashboard
                </a>
            </div>

            <div>
                
                <div class="bg-gray-200 p-1 rounded-lg inline-flex items-center mb-0">
                    
                    <button onclick="openTab('booking')" id="tab-booking" class="px-6 py-2 text-sm font-bold rounded-md shadow bg-white text-gray-900 transition-all">
                        Booking History
                    </button>

                    <button onclick="openTab('personal')" id="tab-personal" class="px-6 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition-all">
                        Personal Information
                    </button>
                    
                </div>

                <div id="content-booking" class="mt-4">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">My Past Bookings</h2>
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Your rental history will appear here later.</p>
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
            const contentPersonal = document.getElementById('content-personal');
            const contentBooking = document.getElementById('content-booking');
            const btnPersonal = document.getElementById('tab-personal');
            const btnBooking = document.getElementById('tab-booking');

            const activeClass = "px-6 py-2 text-sm font-bold rounded-md shadow bg-white text-gray-900 transition-all";
            const inactiveClass = "px-6 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition-all";

            if (tabName === 'personal') {
                contentPersonal.classList.remove('hidden');
                contentBooking.classList.add('hidden');
                
                btnPersonal.className = activeClass;
                btnBooking.className = inactiveClass;
            } else {
                contentPersonal.classList.add('hidden');
                contentBooking.classList.remove('hidden');
                
                btnPersonal.className = inactiveClass;
                btnBooking.className = activeClass;
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('status') === 'profile-updated' || $errors->any())
                openTab('personal');
            @endif
        });
    </script>
</x-app-layout>