@extends('layouts.admin')

@section('header_title', 'Fleet Availability')

@section('content')
    <div class="flex flex-col h-[calc(100vh-8rem)]"> <!-- Fixed height container for scrolling -->
        
        <!-- Filters Bar -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-4 flex flex-wrap gap-4 items-end z-20 sticky top-0">
            <form action="{{ route('admin.vehicle.availability') }}" method="GET" class="flex flex-wrap gap-4 w-full items-end">
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Date Range Start</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#cb5c55] focus:border-[#cb5c55] outline-none">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Date Range End</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#cb5c55] focus:border-[#cb5c55] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Vehicle Type</label>
                    <select name="type_id" class="px-3 py-2 border border-gray-200 rounded-lg text-sm w-40 focus:ring-2 focus:ring-[#cb5c55] focus:border-[#cb5c55] outline-none">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Plate Number</label>
                    <input type="text" name="plate_number" value="{{ request('plate_number') }}" placeholder="e.g. ABC 1234" class="px-3 py-2 border border-gray-200 rounded-lg text-sm w-40 focus:ring-2 focus:ring-[#cb5c55] focus:border-[#cb5c55] outline-none">
                </div>

                <div class="flex-1 flex items-end justify-end gap-2">
                    <a href="{{ route('admin.vehicle.availability') }}" class="px-5 py-2 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition">
                        Reset
                    </a>
                    <button type="submit" class="px-5 py-2 bg-[#cb5c55] text-white font-bold rounded-lg shadow-sm hover:opacity-90 transition flex items-center gap-2">
                        <i class="ri-filter-3-line"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Legend -->
        <div class="flex gap-4 mb-2 px-2 text-xs font-bold text-gray-500 uppercase tracking-widest">
            <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-green-500"></div> Approved</div>
            <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-blue-500"></div> Rented</div>
            <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-orange-400"></div> Pending Verification</div>
            <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-purple-500"></div> Past (Completed)</div>
        </div>

        <!-- Timeline Gantt Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex-1 overflow-hidden flex flex-col">
            
            <!-- Header Row (Dates) -->
            <div class="flex border-b border-gray-100 bg-gray-50">
                <div class="w-48 p-4 flex-shrink-0 border-r border-gray-100 font-bold text-gray-600 text-xs uppercase tracking-wider bg-gray-50 z-10 sticky left-0">
                    Vehicle
                </div>
                <!-- Scrollable Date Header -->
                <div id="header-scroll" class="flex-1 overflow-hidden flex">
                    @foreach($dates as $date)
                        <div class="flex-1 min-w-[100px] border-r border-gray-100 p-2 text-center">
                            <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $date->format('D') }}</div>
                            <div class="text-sm font-bold text-gray-800">{{ $date->format('d M') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Vehicle Rows -->
            <div id="body-scroll" class="overflow-auto flex-1 custom-scrollbar">
                @forelse($vehicles as $vehicle)
                <div class="flex border-b border-gray-100 hover:bg-gray-50 transition group h-24 relative">
                    <!-- Vehicle Info Column -->
                    <div class="w-48 p-3 flex-shrink-0 border-r border-gray-100 bg-white sticky left-0 z-10 flex items-center gap-3 group-hover:bg-gray-50 transition">
                        <div class="w-12 h-8 rounded bg-gray-100 overflow-hidden border border-gray-200 flex-shrink-0">
                            <img src="{{ asset('images/' . $vehicle->vehicle_image) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="overflow-hidden">
                            <div class="font-bold text-gray-800 text-xs truncate">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
                            <div class="text-[10px] font-mono text-gray-500">{{ $vehicle->plate_number }}</div>
                            
                            @if($vehicle->status === 'Maintenance')
                                <span class="text-[9px] px-1.5 py-0.5 bg-red-100 text-red-600 rounded font-bold uppercase mt-1 inline-block">Maintenance</span>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline Columns -->
                    <div class="flex-1 flex relative">
                        <!-- Grid Lines -->
                        @foreach($dates as $index => $date)
                            <div class="flex-1 min-w-[100px] border-r border-gray-50 h-full {{ $date->isToday() ? 'bg-yellow-50/30' : '' }}"></div>
                        @endforeach

                        <!-- Booking Bars -->
                        @foreach($vehicle->bookings as $booking)
                            @php
                                $pickup = \Carbon\Carbon::parse($booking->pickup_date_time);
                                $return = \Carbon\Carbon::parse($booking->return_date_time);
                                
                                // View Boundaries
                                $viewStart = $startDate->copy()->startOfDay();
                                $viewEnd = $endDate->copy()->endOfDay();

                                // Clamp booking to view range
                                $barStart = $pickup->lt($viewStart) ? $viewStart->copy() : $pickup->copy();
                                $barEnd = $return->gt($viewEnd) ? $viewEnd->copy() : $return->copy();

                                if ($barEnd->lte($barStart)) continue; 

                                // Calculation
                                $totalMins = max(1, $viewStart->diffInMinutes($viewEnd));
                                $offsetMins = $viewStart->diffInMinutes($barStart); // Uses absolute diff
                                $durationMins = $barStart->diffInMinutes($barEnd);

                                $leftPercent = ($offsetMins / $totalMins) * 100;
                                $widthPercent = ($durationMins / $totalMins) * 100;

                                $color = 'bg-gray-500'; 
                                $status = strtolower($booking->status);
                                if($status == 'approved') $color = 'bg-green-500/90 hover:bg-green-600';
                                if($status == 'rented') $color = 'bg-blue-500/90 hover:bg-blue-600';
                                if(in_array($status, ['waiting for verification', 'verify receipt', 'pending'])) $color = 'bg-orange-400/90 hover:bg-orange-500';
                                if($status == 'completed') $color = 'bg-purple-500/90 hover:bg-purple-600';
                            @endphp

                            <!-- Bar -->
                            <a href="{{ route('admin.bookings.show_detail', $booking->id) }}" 
                               class="absolute top-1/2 -translate-y-1/2 h-12 rounded-lg shadow-lg {{ $color }} z-[5] text-white text-[10px] flex items-center px-3 overflow-hidden whitespace-nowrap transition cursor-pointer border border-white/40 transform hover:scale-[1.02] hover:z-[20] group/bar"
                               style="left: {{ number_format($leftPercent, 4, '.', '') }}%; width: {{ number_format(max($widthPercent, 0.5), 4, '.', '') }}%; min-width: 8px;"
                               title="{{ $booking->customer_name }} ({{ $pickup->format('d M H:i') }} - {{ $return->format('d M H:i') }})">
                                <div class="flex flex-col leading-tight">
                                    <span class="font-black uppercase tracking-wide truncate drop-shadow-md text-xs">{{ $booking->customer_name ?? 'Guest' }}</span>
                                    <span class="text-[9px] opacity-90 truncate">{{ $pickup->format('H:i') }} - {{ $return->format('H:i') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @empty
                    <div class="p-12 text-center text-gray-400">
                        <i class="ri-car-washing-line text-4xl mb-2"></i>
                        <p>No vehicles found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        const headerScroll = document.getElementById('header-scroll');
        const bodyScroll = document.getElementById('body-scroll');
        if (bodyScroll && headerScroll) {
            bodyScroll.addEventListener('scroll', function() {
                headerScroll.scrollLeft = this.scrollLeft;
            });
        }
    </script>
@endsection
