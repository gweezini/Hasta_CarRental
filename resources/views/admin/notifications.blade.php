@extends('layouts.admin')

@section('header_title', 'Notification Center')

@section('content')
    <div x-data="{ currentTab: '{{ request('filter') ? 'history' : 'active' }}' }" class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div class="flex p-1 bg-gray-200 rounded-xl w-fit shadow-inner">
                <button @click="currentTab = 'active'" 
                        :class="currentTab === 'active' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="px-8 py-3 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                    Active Alerts
                    @if($activeList->count() > 0)
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $activeList->count() }}</span>
                    @endif
                </button>
                <button @click="currentTab = 'history'" 
                        :class="currentTab === 'history' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="px-8 py-3 rounded-lg text-sm font-bold transition-all">
                    Processed Records
                </button>
            </div>

            <div x-show="currentTab === 'history'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95">
                <form action="{{ route('admin.notifications') }}" method="GET" class="flex items-center gap-3">
                    <div class="flex items-center gap-2 text-gray-500 bg-white px-3 py-2 rounded-lg border border-gray-200 shadow-sm">
                        <i class="ri-filter-3-line text-lg text-[#cb5c55]"></i>
                        <span class="text-xs font-bold uppercase tracking-wide hidden sm:block">Filter:</span>
                        <select name="filter" onchange="this.form.submit()" class="text-sm font-medium bg-transparent border-none focus:ring-0 cursor-pointer text-gray-700 hover:text-[#cb5c55] transition outline-none pr-8">
                            <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Today Only</option>
                            <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Time</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden min-h-[450px]">
            
            <div x-show="currentTab === 'active'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0">
                @if($activeList->isEmpty())
                    <div class="py-32 text-center flex flex-col items-center justify-center">
                        <div class="bg-green-50 p-4 rounded-full mb-4">
                            <i class="ri-checkbox-circle-fill text-4xl text-green-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">All Clear!</h3>
                        <p class="text-gray-400 text-base mt-1">No vehicles require immediate attention.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-sm uppercase tracking-wider">
                                    <th class="px-8 py-4 font-bold w-1/3 text-left">Vehicle / Type</th>
                                    <th class="px-8 py-4 font-bold text-left">Expiry Info</th>
                                    <th class="px-8 py-4 font-bold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($activeList as $alert)
                                    <tr class="hover:bg-red-50/20 transition group">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="h-12 w-16 rounded-lg bg-gray-200 flex items-center justify-center overflow-hidden border border-gray-300 shadow-sm shrink-0">
                                                    @if(!empty($alert->vehicle_image))
                                                        <img src="{{ asset('images/' . $alert->vehicle_image) }}" 
                                                             class="h-full w-full object-cover" 
                                                             onerror="this.src='{{ asset('storage/' . $alert->vehicle_image) }}'; this.onerror=function(){this.style.display='none'; this.nextElementSibling.style.display='block';};">
                                                        <i class="ri-car-fill text-2xl text-gray-400 hidden"></i>
                                                    @else
                                                        <i class="ri-car-fill text-2xl text-gray-400"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-base font-bold text-gray-800">{{ $alert->plate_number }}</div>
                                                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $alert->type == 'Road Tax' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                                        {{ $alert->type }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="text-sm font-bold {{ $alert->is_expired ? 'text-red-600' : 'text-yellow-600' }}">
                                                {{ $alert->is_expired ? 'EXPIRED' : 'EXPIRING SOON' }}
                                            </div>
                                            <div class="text-sm text-gray-500 mt-0.5">
                                                Date: <span class="font-mono font-bold text-gray-700">
                                                    @if($alert->date)
                                                        {{ \Carbon\Carbon::parse($alert->date)->format('d M Y') }}
                                                    @else
                                                        <span class="text-gray-400 italic">Not Set</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                {{ $alert->is_expired ? abs(intval($alert->days_left)) . ' days overdue' : intval($alert->days_left) . ' days left' }}
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <a href="{{ route('admin.vehicle.edit', $alert->car_id) }}" class="inline-block px-5 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 shadow-sm hover:bg-[#cb5c55] hover:text-white hover:border-[#cb5c55] transition">
                                                Renew Now
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div x-show="currentTab === 'history'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" style="display: none;">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse table-fixed"> 
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-sm uppercase tracking-wider">
                                <th class="px-8 py-4 font-bold w-[30%] text-left">Vehicle</th> 
                                <th class="px-8 py-4 font-bold w-[25%] text-left">Last Updated</th>
                                <th class="px-8 py-4 font-bold w-[45%] text-right">Document Status</th> 
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentUpdates as $record)
                                <tr class="hover:bg-gray-50 transition">
                                    
                                    <td class="px-8 py-6 align-top">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-16 rounded-lg bg-gray-200 flex items-center justify-center overflow-hidden border border-gray-300 shadow-sm shrink-0">
                                                @if(!empty($record->vehicle_image))
                                                    <img src="{{ asset('images/' . $record->vehicle_image) }}" 
                                                         class="h-full w-full object-cover" 
                                                         onerror="this.src='{{ asset('storage/' . $record->vehicle_image) }}'; this.onerror=function(){this.style.display='none'; this.nextElementSibling.style.display='block';};">
                                                    <i class="ri-car-fill text-2xl text-gray-400 hidden"></i>
                                                @else
                                                    <i class="ri-car-fill text-2xl text-gray-400"></i>
                                                @endif
                                            </div>
                                            <div class="text-base font-bold text-gray-800">{{ $record->plate_number }}</div>
                                        </div>
                                    </td>

                                    <td class="px-8 py-6 align-top">
                                        <div class="text-sm font-medium text-gray-600">{{ $record->updated_at->diffForHumans() }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $record->updated_at->format('d M Y, h:i A') }}</div>
                                    </td>

                                    <td class="px-8 py-6 align-top">
                                        <div class="flex flex-col gap-3 items-end">
                                            
                                            <div class="flex items-center justify-end gap-4"> 
                                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Road Tax:</span>
                                                
                                                @if($record->road_tax_date)
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-bold border shadow-sm w-36 justify-center
                                                        {{ $record->rt_safe 
                                                            ? 'bg-green-50 text-green-700 border-green-200' 
                                                            : 'bg-red-50 text-red-700 border-red-200' 
                                                        }}">
                                                        <i class="{{ $record->rt_safe ? 'ri-checkbox-circle-fill' : 'ri-error-warning-fill' }} mr-2 text-base"></i>
                                                        {{ \Carbon\Carbon::parse($record->road_tax_date)->format('d M Y') }}
                                                    </span>
                                                @else
                                                    <a href="{{ route('admin.vehicle.edit', $record->car_id) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-bold border border-dashed border-gray-300 text-gray-400 hover:text-[#cb5c55] hover:border-[#cb5c55] hover:bg-red-50 transition w-36 justify-center group">
                                                        <i class="ri-add-circle-line mr-2 text-base group-hover:text-[#cb5c55]"></i> Set Now
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="flex items-center justify-end gap-4"> 
                                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Insurance:</span>
                                                
                                                @if($record->insurance_date)
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-bold border shadow-sm w-36 justify-center
                                                        {{ $record->ins_safe 
                                                            ? 'bg-green-50 text-green-700 border-green-200' 
                                                            : 'bg-red-50 text-red-700 border-red-200' 
                                                        }}">
                                                        <i class="{{ $record->ins_safe ? 'ri-checkbox-circle-fill' : 'ri-error-warning-fill' }} mr-2 text-base"></i>
                                                        {{ \Carbon\Carbon::parse($record->insurance_date)->format('d M Y') }}
                                                    </span>
                                                @else
                                                    <a href="{{ route('admin.vehicle.edit', $record->car_id) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-bold border border-dashed border-gray-300 text-gray-400 hover:text-[#cb5c55] hover:border-[#cb5c55] hover:bg-red-50 transition w-36 justify-center group">
                                                        <i class="ri-add-circle-line mr-2 text-base group-hover:text-[#cb5c55]"></i> Set Now
                                                    </a>
                                                @endif
                                            </div>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($recentUpdates->isEmpty())
                        <div class="py-20 text-center text-gray-400">
                            <p>No recently processed records for this period.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
@endsection
