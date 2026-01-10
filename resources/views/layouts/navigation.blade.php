<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if(Auth::user() && Auth::user()->role === 'admin') 
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Admin Panel') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.vehicle.index')" :active="request()->routeIs('admin.vehicle.index')">
                            {{ __('Car List') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.index')">
                            {{ __('Customer List') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                
                <div x-data="{ notifOpen: false }" class="relative mr-4">
                    <button @click="notifOpen = ! notifOpen" class="relative p-2 text-gray-400 hover:text-gray-600 transition focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                        @endif
                    </button>

                    <div x-show="notifOpen" 
                         @click.away="notifOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50 border border-gray-100"
                         style="display: none;">
                        
                        <div class="py-2">
                            <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-700">Notifications</span>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">
                                        {{ Auth::user()->unreadNotifications->count() }} New
                                    </span>
                                @endif
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                @forelse(Auth::user()->notifications->take(3) as $notification)
                            <a href="{{ route('profile.edit', ['tab' => 'booking']) }}#booking-{{ $notification->data['booking_id'] ?? '' }}" 
                               class="block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition flex items-start gap-3">

                                            <div class="flex-shrink-0 mt-0.5">
                                                @if(isset($notification->data['status']) && $notification->data['status'] == 'Approved')
                                                    <div class="h-6 w-6 rounded-full bg-green-100 text-green-500 flex items-center justify-center">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                @elseif(isset($notification->data['status']) && $notification->data['status'] == 'Rejected')
                                                    <div class="h-6 w-6 rounded-full bg-red-100 text-red-500 flex items-center justify-center">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </div>
                                                @else
                                                    <div class="h-6 w-6 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3 w-0 flex-1">
                                                <p class="text-xs font-medium text-gray-900 truncate">
                                                    {{ $notification->data['message'] ?? 'New Notification' }}
                                                </p>
                                                <p class="text-[10px] text-gray-500 mt-1">
                                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                </p>
                                            </div>
                            </a>
                                @empty
                                    <div class="px-4 py-6 text-center text-gray-400 text-xs">
                                        No notifications yet.
                                    </div>
                                @endforelse
                            </div>

                            <a href="{{ route('profile.edit', ['tab' => 'notifications']) }}" class="block text-center text-xs font-bold text-[#cb5c55] py-2 hover:bg-gray-50 transition border-t border-gray-100 mt-1">
                                View All Notifications &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 mr-4">
                    {{ Auth::user()->name }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                @csrf
                    <button type="submit" class="px-4 py-2 text-white text-sm font-bold rounded-lg hover:opacity-90 transition" style="background-color: #ec5a29 ">
                        Log Out
                    </button>
                </form>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('profile.edit', ['tab' => 'notifications'])">
                {{ __('Notifications') }} 
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="ml-2 bg-red-100 text-red-600 px-2 py-0.5 rounded-full text-xs font-bold">{{ Auth::user()->unreadNotifications->count() }}</span>
                @endif
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
