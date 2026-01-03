<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Details - Hasta Car Rental</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap");
        @import url("https://fonts.googleapis.com/css2?family=Syncopate:wght@400;700&display=swap");

        :root {
            --primary-color: #ec5a29;
            --text-dark: #15191d;
            --text-light: #737373;
            --white: #ffffff;
            --header-font: "Syncopate", sans-serif;
        }

        body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }

        /* --- NAVIGATION STYLES --- */
        nav {
            background-color: #2d3748;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .nav__btn { display: flex; align-items: center; gap: 1.5rem; }

        footer { background-color: var(--text-dark); padding-top: 3rem; color: #fff; margin-top: auto; }
        .footer__container { 
            max-width: 1200px; margin: auto; padding: 0 1rem 2rem; 
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem;
            border-bottom: 1px solid #333;
        }
        .footer__col h4 { font-family: var(--header-font); margin-bottom: 1.5rem; }
        .footer__links li { margin-bottom: 0.8rem; list-style: none; }
        .footer__links a { color: var(--text-light); text-decoration: none; }
        .footer__links a:hover { color: var(--primary-color); }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <nav>
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="hover:opacity-80 transition">
                <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta Logo" class="h-10 rounded shadow border-2 border-white/20">
            </a>
        </div>

        <div class="nav__btn">
            
            <div x-data="{ open: false }" class="relative flex items-center">
                <button @click="open = !open" @click.away="open = false" class="relative text-white hover:text-[#ec5a29] transition p-1">
                    <i class="ri-notification-3-line text-2xl"></i>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-0 right-0 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600 border-2 border-[#15191d]"></span>
                        </span>
                    @endif
                </button>

                <div x-show="open" style="display: none;" 
                     x-transition.origin.top.right
                     class="absolute right-0 top-full mt-3 w-80 bg-white rounded-xl shadow-2xl overflow-hidden z-50 border border-gray-100 text-gray-800">
                    
                    <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center bg-gray-50">
                        <span class="text-xs font-bold uppercase text-gray-500 tracking-wider">Notifications</span>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">{{ Auth::user()->unreadNotifications->count() }} New</span>
                        @endif
                    </div>

                    <div class="max-h-64 overflow-y-auto">
                        @forelse(Auth::user()->notifications->take(3) as $notification)
                            <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition flex items-start gap-3">
                                <div class="mt-1">
                                    @if(isset($notification->data['status']) && $notification->data['status'] == 'Approved')
                                        <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><i class="ri-check-line text-xs"></i></div>
                                    @elseif(isset($notification->data['status']) && $notification->data['status'] == 'Rejected')
                                        <div class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center"><i class="ri-close-line text-xs"></i></div>
                                    @else
                                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="ri-notification-line text-xs"></i></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 leading-tight">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center text-xs text-gray-400">No notifications</div>
                        @endforelse
                    </div>
                    <a href="{{ route('profile.edit', ['tab' => 'notifications']) }}" class="w-full text-center text-xs font-bold text-[#ec5a29] py-3 hover:bg-gray-50 transition border-t border-gray-100 block">
                        View All
                    </a>
                </div>
            </div>

            <div x-data="{ userOpen: false }" class="relative">
                <button @click="userOpen = !userOpen" @click.away="userOpen = false" class="flex items-center gap-2 group focus:outline-none">
                    <img class="h-9 w-9 rounded-full object-cover border-2 border-transparent group-hover:border-[#ec5a29] transition" 
                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ec5a29&color=fff" 
                         alt="Profile">
                    <span class="text-white text-sm font-medium hidden md:block group-hover:text-[#ec5a29] transition">{{ Auth::user()->name }}</span>
                    <i class="ri-arrow-down-s-line text-gray-400 group-hover:text-[#ec5a29] transition"></i>
                </button>

                <div x-show="userOpen" style="display: none;"
                     x-transition.origin.top.right
                     class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl overflow-hidden z-50 border border-gray-100 py-1">
                    
                    <div class="px-4 py-3 border-b border-gray-50">
                        <p class="text-xs text-gray-500">Signed in as</p>
                        <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                    </div>

                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#ec5a29]">
                        <i class="ri-home-line mr-2 align-middle"></i> Home
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#ec5a29]">
                        <i class="ri-user-line mr-2 align-middle"></i> My Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">
                            <i class="ri-logout-box-r-line mr-2 align-middle"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <div class="mb-6">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-5 py-2.5 bg-[#15191d] text-white text-xs font-bold uppercase tracking-widest rounded-lg hover:bg-[#ec5a29] transition shadow-md hover:shadow-lg transform active:scale-95">
                    <i class="ri-arrow-left-line mr-2 text-base"></i> 
                    Back to Profile
                </a>
            </div>

            @include('inspections.partials.report_card')
        </div>
    </main>

    <footer>
        <div class="footer__container">
            <div class="footer__col">
                <h4>Resources</h4>
                <ul class="footer__links">
                    <li><a href="#">Installation Manual</a></li>
                    <li><a href="#">Release Note</a></li>
                    <li><a href="#">Community Help</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>Company</h4>
                <ul class="footer__links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Career</a></li>
                    <li><a href="#">Press</a></li>
                    <li><a href="#">Support</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>Product</h4>
                <ul class="footer__links">
                    <li><a href="#">Demo</a></li>
                    <li><a href="#">Security</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>Follow Us</h4>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-[#ec5a29] text-xl"><i class="ri-facebook-fill"></i></a>
                    <a href="#" class="text-gray-400 hover:text-[#ec5a29] text-xl"><i class="ri-instagram-fill"></i></a>
                    <a href="#" class="text-gray-400 hover:text-[#ec5a29] text-xl"><i class="ri-twitter-fill"></i></a>
                </div>
            </div>
        </div>
        <div class="text-center text-gray-500 pb-8 text-sm">
            © 2025 Hasta Car Rental. All rights reserved.
        </div>
    </footer>
</body>
</html>
