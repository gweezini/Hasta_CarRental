@extends('layouts.admin')

@section('header_title', '') 

@section('content')
    <div class="hidden print:block mb-10 border-b-4 border-gray-800 pb-6 text-center">
        <h1 class="text-4xl font-black text-gray-900 uppercase tracking-widest">Hasta Car Rental</h1>
        <h2 class="text-2xl font-bold text-gray-600 mt-2">Financial Revenue Report</h2>
        <p class="text-sm text-gray-400 mt-4 font-medium">Generated on: {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <div class="flex justify-between items-end mb-8 no-print">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Financial Reports</h2>
            <p class="text-gray-500 text-sm">Analyze revenue performance over time</p>
        </div>
        <button onclick="window.print()" class="flex items-center gap-2 px-5 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition shadow">
            <i class="ri-printer-line"></i> Print Report
        </button>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-8 flex justify-between items-center no-print">
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
                    <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Monthly (This Month)</option>
                    <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Yearly (All Time)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <i class="ri-arrow-down-s-line"></i>
                </div>
            </div>
        </form>
    </div>

    {{-- 核心修改：强制打印时显示三列 (flex print:flex-row) --}}
    <div class="flex flex-col md:flex-row gap-6 mb-8 print:flex-row print:justify-between print:gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex-1 flex items-center justify-between print:border-gray-200 print:p-4">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Transactions</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1 print:text-lg">{{ $totalTransactions ?? 0 }}</h3>
                <p class="text-[10px] text-gray-400">In this period</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 no-print">
                <i class="ri-shopping-cart-2-line text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex-1 flex items-center justify-between print:border-gray-200 print:p-4">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Avg. Transaction</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1 print:text-lg">RM {{ number_format($avgOrderValue ?? 0, 0) }}</h3>
                <p class="text-[10px] text-gray-400">Revenue / Count</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-500 no-print">
                <i class="ri-scales-3-line text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex-1 flex items-center justify-between print:border-gray-200 print:p-4">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Highest Record</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1 print:text-lg">RM {{ number_format($highestTransaction ?? 0, 0) }}</h3>
                <p class="text-[10px] text-gray-400">Best performance</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 no-print">
                <i class="ri-trophy-line text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 print:border-gray-200">
        <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center no-print">
            <h3 class="font-bold text-gray-800 flex items-center gap-2 capitalize">
                <i class="ri-money-dollar-circle-line text-[#cb5c55] no-print"></i> {{ $filter }} Revenue Breakdown
            </h3>
        </div>
        
        <div class="p-6 print:p-0">
            @if(count($formattedRevenue) > 0)
                <table class="w-full text-left print:text-xs">
                    <thead class="border-b-2 border-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest print:text-gray-800">
                        <tr>
                            <th class="py-4 pl-4">Time Period</th>
                            <th class="py-4 text-center no-print">Volume & Density</th>
                            <th class="py-4 text-right pr-4">Amount (RM)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($formattedRevenue as $label => $amount)
                        <tr class="group hover:bg-gray-50 transition">
                            <td class="py-5 pl-4">
                                <span class="font-bold text-gray-700 text-base block leading-tight print:text-sm">{{ $label }}</span>
                                <span class="text-[9px] text-gray-400 uppercase font-medium tracking-tighter">Verified Entry</span>
                            </td>
                            
                            <td class="py-5 text-center px-4 no-print">
                                <div class="inline-flex items-center gap-4">
                                    <div class="flex items-center gap-2 px-3 py-1 bg-gray-100 text-gray-600 rounded-lg border border-gray-200 shadow-sm transition group-hover:bg-white">
                                        <i class="ri-ticket-2-line text-xs text-[#cb5c55]"></i>
                                        <span class="text-[10px] font-black tracking-tight uppercase">
                                            1 TRANSACTION
                                        </span>
                                    </div>
                                    @php 
                                        $percentage = $formattedRevenue->sum() > 0 ? ($amount / $formattedRevenue->sum()) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center gap-1.5 px-3 py-1 bg-red-50 text-[#cb5c55] rounded-lg border border-red-100">
                                        <i class="ri-pie-chart-line text-xs"></i>
                                        <span class="text-[10px] font-bold">{{ number_format($percentage, 0) }}% OF TOTAL</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-5 text-right pr-4">
                                <div class="flex flex-col items-end">
                                    <span class="font-black text-[#cb5c55] text-xl print:text-sm">RM {{ number_format($amount, 2) }}</span>
                                    <span class="text-[9px] text-green-500 font-bold uppercase tracking-tighter flex items-center gap-1 no-print">
                                        <i class="ri-checkbox-circle-fill"></i> Verified Payment
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-4 border-gray-100 bg-gray-50/50 print:bg-white">
                        <tr>
                            <td colspan="2" class="py-6 font-black text-gray-800 text-right pr-6 text-sm uppercase tracking-widest print:text-base">Total Period Revenue</td>
                            <td class="py-6 text-right font-black text-2xl text-gray-900 pr-4 print:text-lg">
                                RM {{ number_format($formattedRevenue->sum(), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="text-center py-20 text-gray-400">
                    <i class="ri-file-search-line text-6xl mb-4 block opacity-20"></i>
                    <p class="text-base font-medium">No payment records found for this period.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        @media print {
            body::after {
                content: "HASTA CAR RENTAL - OFFICIAL FINANCIAL LEDGER - PAGE 1";
                position: fixed;
                bottom: 10mm;
                left: 0;
                right: 0;
                text-align: center;
                font-size: 8px;
                color: #aaa;
                font-weight: bold;
                letter-spacing: 2px;
            }
        }
    </style>
@endsection