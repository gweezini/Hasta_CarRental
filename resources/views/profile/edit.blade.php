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
                
                {{-- 🔥 TAB 按钮区域 (新增了 Notifications) 🔥 --}}
                <div class="bg-gray-200 p-1 rounded-lg inline-flex items-center mb-0 overflow-x-auto max-w-full">
                    
                    <button onclick="openTab('booking')" id="tab-booking" class="px-6 py-2 text-sm font-bold rounded-md shadow bg-white text-gray-900 transition-all whitespace-nowrap mr-2">
                        Booking History
                    </button>

                    <button onclick="openTab('rewards')" id="tab-rewards" class="px-6 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition-all whitespace-nowrap mr-2">
                        My Rewards
                    </button>

                    <button onclick="openTab('personal')" id="tab-personal" class="px-6 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition-all whitespace-nowrap mr-2">
                        Personal Info
                    </button>

                    {{-- 🔥 Notification Tab 按钮 🔥 --}}
                    <button onclick="openTab('notifications')" id="tab-notifications" class="relative px-6 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition-all whitespace-nowrap">
                        <i class="ri-notification-3-line text-lg align-middle"></i> Notifications
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-2 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>
                    
                </div>

                @php
                    $allBookings = $bookings ?? collect();
                    $now = \Carbon\Carbon::now();

                    // Past: 状态已结束 OR 时间已过期
                    $pastBookings = $allBookings->filter(function($b) use ($now) {
                        $isClosed = in_array($b->status, ['Rejected', 'Returned', 'Completed']);
                        $isExpired = $b->return_date_time && \Carbon\Carbon::parse($b->return_date_time)->lt($now);
                        return $isClosed || $isExpired;
                    });

                    // Ongoing: 剩下的都是进行中
                    $ongoingBookings = $allBookings->diff($pastBookings);
                @endphp

                {{-- 1. Booking History Content --}}
                <div id="content-booking" class="mt-4">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Ongoing Bookings</h2>
                        
                        @if($ongoingBookings->count() > 0)
                            <div class="space-y-4 mb-8">
                                @foreach($ongoingBookings as $booking)
                                    <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h3 class="font-semibold text-gray-900">Booking ID: #{{ $booking->id }}</h3>
                                                <p class="text-sm text-gray-600">{{ $booking->vehicle?->model ?? 'N/A' }} ({{ $booking->vehicle?->plate_number ?? 'N/A' }})</p>
                                            </div>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                {{ $booking->status == 'Waiting for Verification' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <p class="text-gray-500">Pickup</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $booking->pickup_date_time ? \Carbon\Carbon::parse($booking->pickup_date_time)->format('M d, Y h:i A') : 'Not Set' }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Return</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $booking->return_date_time ? \Carbon\Carbon::parse($booking->return_date_time)->format('M d, Y h:i A') : 'Not Set' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-blue-200">
                                            <p class="text-lg font-semibold text-gray-900">Total: RM {{ number_format($booking->total_rental_fee, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-blue-50 rounded-lg border border-blue-200 mb-8">
                                <p class="text-sm text-gray-600">No ongoing bookings</p>
                            </div>
                        @endif

                        <h2 class="text-lg font-medium text-gray-900 mb-4 mt-8">Past Bookings</h2>
                        
                        @if($pastBookings->count() > 0)
                            <div class="space-y-4">
                                @foreach($pastBookings as $booking)
                                    <div class="border border-gray-300 rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h3 class="font-semibold text-gray-900">Booking ID: #{{ $booking->id }}</h3>
                                                <p class="text-sm text-gray-600">{{ $booking->vehicle?->model ?? 'N/A' }} ({{ $booking->vehicle?->plate_number ?? 'N/A' }})</p>
                                            </div>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                @if($booking->status === 'Completed' || $booking->status === 'Returned') bg-green-100 text-green-800 
                                                @elseif($booking->status === 'Rejected') bg-red-100 text-red-800 
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <p class="text-gray-500">Pickup</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $booking->pickup_date_time ? \Carbon\Carbon::parse($booking->pickup_date_time)->format('M d, Y h:i A') : 'Not Set' }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Return</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $booking->return_date_time ? \Carbon\Carbon::parse($booking->return_date_time)->format('M d, Y h:i A') : 'Not Set' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <p class="text-sm font-semibold text-gray-900">Total: RM {{ number_format($booking->total_rental_fee, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                <svg class="mx-auto text-gray-400" style="width: 48px; height: 48px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No past bookings yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Your rental history will appear here.</p>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- 2. Rewards Content --}}
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

                {{-- 3. Personal Content --}}
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

                {{-- 🔥 4. Notification Content (新增部分) 🔥 --}}
                <div id="content-notifications" class="hidden mt-4">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-lg font-medium text-gray-900">Notifications</h2>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                    {{ Auth::user()->unreadNotifications->count() }} new
                                </span>
                            @endif
                        </div>

                        <div class="space-y-4">
                            @forelse(Auth::user()->notifications as $notification)
                                <div class="flex items-start p-4 rounded-lg border {{ $notification->read_at ? 'bg-white border-gray-200' : 'bg-blue-50 border-blue-200' }}">
                                    <div class="flex-shrink-0 mr-3">
                                        @if(isset($notification->data['status']) && $notification->data['status'] == 'Approved')
                                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                                <i class="ri-check-line"></i>
                                            </div>
                                        @elseif(isset($notification->data['status']) && $notification->data['status'] == 'Rejected')
                                            <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                                <i class="ri-close-line"></i>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center">
                                                <i class="ri-notification-line"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $notification->data['message'] ?? 'No message' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if(!$notification->read_at)
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2" title="Unread"></div>
                                        {{ $notification->markAsRead() }} 
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-12 text-gray-400">
                                    <i class="ri-notification-off-line text-4xl mb-2 block"></i>
                                    <p>No notifications yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        function openTab(tabName) {
            const tabs = ['booking', 'rewards', 'personal', 'notifications'];
            
            // 样式定义
            const activeClass = "px-6 py-2 text-sm font-bold rounded-md shadow bg-white text-gray-900 transition-all whitespace-nowrap mr-2";
            const inactiveClass = "px-6 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition-all whitespace-nowrap mr-2";
            
            // Notification 特殊样式 (因为它带红点)
            const notifActive = "relative px-6 py-2 text-sm font-bold rounded-md shadow bg-white text-gray-900 transition-all whitespace-nowrap";
            const notifInactive = "relative px-6 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition-all whitespace-nowrap";

            tabs.forEach(t => {
                // 隐藏所有内容
                const content = document.getElementById('content-' + t);
                if(content) content.classList.add('hidden');
                
                // 重置所有按钮
                const btn = document.getElementById('tab-' + t);
                if(btn) {
                    if(t === 'notifications') {
                        btn.className = notifInactive;
                    } else {
                        btn.className = inactiveClass;
                    }
                }
            });

            // 显示选中内容
            const activeContent = document.getElementById('content-' + tabName);
            if(activeContent) activeContent.classList.remove('hidden');

            // 激活选中按钮
            const activeBtn = document.getElementById('tab-' + tabName);
            if(activeBtn) {
                if(tabName === 'notifications') {
                    activeBtn.className = notifActive;
                } else {
                    activeBtn.className = activeClass;
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');

            if (tabParam === 'notifications') {
                openTab('notifications');
            } else if ("{{ session('status') }}" === 'profile-updated' || "{{ $errors->any() }}") {
                openTab('personal');
            } else {
                openTab('booking');
            }
        });
    </script>
</x-app-layout>