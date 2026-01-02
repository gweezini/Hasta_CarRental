<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <title>Financial Reports - Hasta Admin</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");
        body { font-family: 'Poppins', sans-serif; }
        .theme-text { color: #cb5c55; }
        .theme-bg { background-color: #cb5c55; }
        /* 侧边栏高亮 */
        .nav-active { background-color: rgba(255,255,255,0.2); box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06); }
    </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex">
        
        <aside class="w-64 bg-[#cb5c55] text-white flex flex-col fixed top-0 left-0 h-full shadow-lg z-50 overflow-y-auto">
            <div class="p-6 text-center border-b border-white/20">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta Logo" class="w-32 mx-auto rounded-lg shadow-sm">
                </a>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="block py-3 px-4 rounded text-white transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 shadow-inner font-medium' : 'hover:bg-white/10' }}">
                    <i class="ri-dashboard-line mr-2"></i> Dashboard
                </a>

                <a href="{{ route('admin.bookings.index') }}" 
                   class="block py-3 px-4 rounded text-white transition {{ request()->routeIs('admin.bookings*') ? 'bg-white/20 shadow-inner font-medium' : 'hover:bg-white/10' }}">
                    <i class="ri-list-check-2 mr-2"></i> Bookings
                </a>

                <a href="{{ route('admin.vehicle.index') }}" 
                   class="block py-3 px-4 rounded text-white transition {{ request()->routeIs('admin.vehicle*') ? 'bg-white/20 shadow-inner font-medium' : 'hover:bg-white/10' }}">
                    <i class="ri-car-line mr-2"></i> Fleet Management
                </a>
    
                <a href="{{ route('admin.customers.index') }}" 
                   class="block py-3 px-4 rounded text-white transition {{ request()->routeIs('admin.customers*') ? 'bg-white/20 shadow-inner font-medium' : 'hover:bg-white/10' }}">
                    <i class="ri-group-line mr-2"></i> Customers
                </a>

                @if(Auth::user()->isTopManagement())
                <a href="{{ route('admin.reports') }}" 
                   class="block py-3 px-4 rounded text-white transition {{ request()->routeIs('admin.reports') ? 'bg-white/20 shadow-inner font-medium' : 'hover:bg-white/10' }}">
                      <i class="ri-file-chart-line mr-2"></i> Reports
                </a>
                @endif
                
                </nav>

            <div class="p-4 border-t border-white/20 mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full py-2 bg-white text-[#cb5c55] rounded hover:bg-gray-100 transition font-bold shadow-md">
                        <i class="ri-logout-box-line mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-8 ml-64">
            
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Financial Reports</h2>
                    <p class="text-gray-500 text-sm">Analyze revenue performance over time</p>
                </div>
                <button onclick="window.print()" class="flex items-center gap-2 px-5 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition shadow">
                    <i class="ri-printer-line"></i> Print Report
                </button>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-8 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-red-50 p-2 rounded-lg text-[#cb5c55]">
                        <i class="ri-bar-chart-grouped-line text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">Performance Overview</h3>
                        <p class="text-xs text-gray-500">Showing data for: <span class="font-bold text-[#cb5c55] capitalize">{{ $filter }}</span></p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.reports') }}" class="flex items-center gap-3">
                    <label for="filter" class="text-sm font-medium text-gray-600">
                        <i class="ri-filter-3-line align-middle mr-1"></i> Filter By:
                    </label>
                    
                    <div class="relative">
                        <select name="filter" onchange="this.form.submit()" 
                                class="appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-2 pl-4 pr-10 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-[#cb5c55] text-sm font-medium cursor-pointer hover:bg-gray-100 transition shadow-sm">
                            <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Daily (Last 30 Days)</option>
                            <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Weekly (Last 12 Weeks)</option>
                            <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Monthly (This Year)</option>
                            <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Yearly (All Time)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition hover:-translate-y-1 duration-300">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Transactions</p>
                        <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $totalTransactions ?? 0 }}</h3>
                        <p class="text-[10px] text-gray-400">In this period</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                        <i class="ri-shopping-cart-2-line text-xl"></i>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition hover:-translate-y-1 duration-300">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Avg. Transaction</p>
                        <h3 class="text-2xl font-black text-gray-800 mt-1">RM {{ number_format($avgOrderValue ?? 0, 0) }}</h3>
                        <p class="text-[10px] text-gray-400">Revenue / Count</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                        <i class="ri-scales-3-line text-xl"></i>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition hover:-translate-y-1 duration-300">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Highest Record</p>
                        <h3 class="text-2xl font-black text-gray-800 mt-1">RM {{ number_format($highestTransaction ?? 0, 0) }}</h3>
                        <p class="text-[10px] text-gray-400">Best performance</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-500">
                        <i class="ri-trophy-line text-xl"></i>
                    </div>
                </div>

            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 capitalize">
                        <i class="ri-money-dollar-circle-line theme-text"></i> {{ $filter }} Revenue Breakdown
                    </h3>
                </div>
                
                <div class="p-6">
                    @if(count($formattedRevenue) > 0)
                        <table class="w-full text-left">
                            <thead class="border-b-2 border-gray-100 text-gray-500 text-xs uppercase">
                                <tr>
                                    <th class="py-3 pl-4">Period</th>
                                    <th class="py-3">Visual Performance</th>
                                    <th class="py-3 text-right pr-4">Amount (RM)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($formattedRevenue as $label => $amount)
                                <tr class="group hover:bg-gray-50 transition">
                                    <td class="py-4 pl-4 font-medium text-gray-700 w-1/4">{{ $label }}</td>
                                    <td class="py-4 w-1/2">
                                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden flex">
                                            <div class="h-full theme-bg transition-all duration-1000 ease-out" style="width: {{ min(100, ($amount / 5000) * 100) }}%"></div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right pr-4 font-bold theme-text w-1/4">RM {{ number_format($amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-t-2 border-gray-100 bg-gray-50/50">
                                <tr>
                                    <td colspan="2" class="py-4 font-bold text-gray-800 text-right pr-4">TOTAL PERIOD REVENUE</td>
                                    <td class="py-4 text-right font-black text-xl text-gray-900 pr-4">
                                        RM {{ number_format($formattedRevenue->sum(), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div class="text-center py-12 text-gray-400">
                            <i class="ri-file-search-line text-5xl mb-3 block opacity-30"></i>
                            <p class="text-sm">No payment records found for this period.</p>
                        </div>
                    @endif
                </div>
            </div>

        </main>
    </div>

</body>
</html>