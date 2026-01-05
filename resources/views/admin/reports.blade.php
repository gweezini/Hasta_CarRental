@extends('layouts.admin')

@section('header_title', '') 

@section('content')
    <div class="hidden print:block mb-10 border-b-4 border-gray-800 pb-6 text-center">
        <h1 class="text-4xl font-black text-gray-900 uppercase tracking-widest">Hasta Car Rental</h1>
        <h2 class="text-2xl font-bold text-gray-600 mt-2">Financial Revenue Report</h2>
        <p class="text-sm text-gray-400 mt-4 font-medium">Generated on: {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <div class="flex justify-between items-end mb-8 no-print text-left">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Financial Reports</h2>
            <p class="text-gray-500 text-sm">Analyze revenue performance over time</p>
        </div>
        <button onclick="window.print()" class="flex items-center gap-2 px-5 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition shadow">
            <i class="ri-printer-line"></i> Print Report
        </button>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-8 flex justify-between items-center no-print">
        <div class="flex items-center gap-2 text-left">
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

    <div class="flex flex-col md:flex-row gap-6 mb-8 print:flex-row print:justify-between print:gap-4">
        {{-- Gross Revenue --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex-1 flex items-center justify-between border-l-4 border-l-green-500 text-left">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Gross Revenue</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1 print:text-lg">RM {{ number_format($totalRevenueAmount ?? 0, 2) }}</h3>
                <p class="text-[10px] text-green-500 font-bold">Total rental income</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-500 no-print">
                <i class="ri-money-dollar-circle-line text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex-1 flex items-center justify-between border-l-4 border-l-red-500 text-left">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Staff Salaries</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1 print:text-lg">- RM {{ number_format($totalSalaries ?? 0, 2) }}</h3>
                <p class="text-[10px] text-red-500 font-bold">Total payroll cost</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-500 no-print">
                <i class="ri-user-star-line text-xl"></i>
            </div>
        </div>

        {{-- Claims Expenses --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex-1 flex items-center justify-between border-l-4 border-l-orange-500 text-left">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Claim Payouts</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1 print:text-lg">- RM {{ number_format($totalClaims ?? 0, 2) }}</h3>
                <p class="text-[10px] text-orange-500 font-bold">Total Approved</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 no-print">
                <i class="ri-hand-coin-line text-xl"></i>
            </div>
        </div>

        {{-- Net Profit --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex-1 flex items-center justify-between border-l-4 border-l-blue-500 bg-blue-50/10 text-left">
            <div>
                <p class="text-xs text-blue-600 font-bold uppercase tracking-wider">Final Net Profit</p>
                <h3 class="text-2xl font-black text-blue-700 mt-1 print:text-lg">RM {{ number_format($netProfit ?? 0, 2) }}</h3>
                <p class="text-[10px] text-blue-400 font-bold uppercase tracking-tighter">Earnings after payroll</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 no-print">
                <i class="ri-line-chart-line text-xl"></i>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-6 mb-8 print:flex-row print:justify-between print:gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex-1 flex items-center justify-between print:border-gray-200 print:p-4 text-left">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Transactions</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1 print:text-lg">{{ $totalTransactions ?? 0 }}</h3>
                <p class="text-[10px] text-gray-400">In this period</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 no-print">
                <i class="ri-shopping-cart-2-line text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex-1 flex items-center justify-between print:border-gray-200 print:p-4 text-left">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Avg. Transaction</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1 print:text-lg">RM {{ number_format($avgOrderValue ?? 0, 0) }}</h3>
                <p class="text-[10px] text-gray-400">Revenue / Count</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-500 no-print">
                <i class="ri-scales-3-line text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex-1 flex items-center justify-between print:border-gray-200 print:p-4 text-left">
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

    {{-- Graph and Comparison Section --}}
    <div class="flex flex-col lg:flex-row gap-6 mb-8 print:block">
        {{-- Chart Section --}}
        <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100 p-6 print:border-gray-200">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="ri-line-chart-fill text-[#cb5c55]"></i> Revenue Trend ({{ ucfirst($filter) }})
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Comparison Widget --}}
        <div class="w-full lg:w-1/3 bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center print:border-gray-200 print:mt-4">
            <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide mb-1 text-gray-500">Period Comparison</h3>
            <p class="text-xs text-gray-400 mb-6">Comparing current {{ $filter }} vs previous {{ $filter }}</p>

            <div class="flex items-center gap-4 mb-6">
                <div class="p-3 rounded-full {{ $comparisonData['is_positive'] ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500' }}">
                    <i class="{{ $comparisonData['is_positive'] ? 'ri-arrow-right-up-line' : 'ri-arrow-right-down-line' }} text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black {{ $comparisonData['is_positive'] ? 'text-green-600' : 'text-red-500' }}">
                        {{ $comparisonData['percentage'] }}%
                    </h2>
                    <p class="text-xs font-bold text-gray-500 uppercase">{{ $comparisonData['is_positive'] ? 'Increase' : 'Decrease' }} {{ $comparisonData['label'] }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                    <span class="text-sm text-gray-500">Current Period</span>
                    <span class="font-bold text-gray-800">RM {{ number_format($comparisonData['current'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                    <span class="text-sm text-gray-500">Previous Period</span>
                    <span class="font-bold text-gray-800">RM {{ number_format($comparisonData['previous'], 2) }}</span>
                </div>
            </div>

            @if($comparisonData['is_positive'])
            <div class="mt-6 bg-green-50 p-3 rounded-lg border border-green-100 text-center">
                <p class="text-green-700 text-xs font-bold">Great job! Performance is trending up. 🚀</p>
            </div>
            @else
            <div class="mt-6 bg-red-50 p-3 rounded-lg border border-red-100 text-center">
                <p class="text-red-700 text-xs font-bold">Revenue is down. Review strategies. 📉</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(203, 92, 85, 0.5)'); // Brand color #cb5c55
        gradient.addColorStop(1, 'rgba(203, 92, 85, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Revenue (RM)',
                    data: {!! json_encode($chartValues) !!},
                    borderColor: '#cb5c55',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#cb5c55',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#f3f4f6',
                        bodyColor: '#f3f4f6',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: RM ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    </script>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 print:border-gray-200">
        <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center no-print text-left">
            <h3 class="font-bold text-gray-800 flex items-center gap-2 capitalize">
                <i class="ri-money-dollar-circle-line text-[#cb5c55] no-print"></i> {{ $filter }} Revenue Breakdown
            </h3>
        </div>
        
        <div class="p-6 print:p-0">
            @if(count($formattedRevenue) > 0)
                <table class="w-full text-left print:text-xs border-collapse">
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
                            <td class="py-5 pl-4 text-left">
                                <span class="font-bold text-gray-700 text-base block leading-tight print:text-sm">{{ $label }}</span>
                                <span class="text-[9px] text-gray-400 uppercase font-bold tracking-tighter">Verified Entry</span>
                            </td>
                            
                            <td class="py-5 text-center px-4 no-print">
                                <div class="inline-flex items-center gap-4">
                                    <div class="flex items-center gap-2 px-3 py-1 bg-gray-100 text-gray-600 rounded-lg border border-gray-200 shadow-sm transition group-hover:bg-white">
                                        <i class="ri-ticket-2-line text-xs text-[#cb5c55]"></i>
                                        <span class="text-[10px] font-black tracking-tight uppercase">
                                            @php
                                                $count = $revenueList->filter(function($item) use ($label, $filter) {
                                                    if ($filter == 'daily') return \Carbon\Carbon::parse($item->updated_at)->format('d M') == $label;
                                                    if ($filter == 'monthly') return \Carbon\Carbon::parse($item->updated_at)->format('F') == $label;
                                                    return false;
                                                })->count();
                                            @endphp
                                            {{ $count > 0 ? $count : 1 }} TRANSACTION{{ $count > 1 ? 'S' : '' }}
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
                                        <i class="ri-checkbox-circle-fill"></i> Verified
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
