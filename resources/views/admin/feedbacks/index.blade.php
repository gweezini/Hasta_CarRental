@extends('layouts.admin')

@section('header_title', 'Customer Feedbacks')

@section('content')
{{-- Integrated Tab Filters --}}
<div class="mb-6">
    <div class="inline-flex p-1 bg-white border border-gray-100 rounded-2xl shadow-sm">
        <a href="{{ route('admin.feedbacks.index') }}" 
           class="px-6 py-2 rounded-[0.9rem] text-[10px] font-black uppercase tracking-widest transition-all duration-300 {{ !request('maintenance') ? 'bg-gray-800 text-white shadow-md' : 'text-gray-400 hover:text-gray-800' }}">
            All Feedbacks
        </a>
        <a href="{{ route('admin.feedbacks.index', ['maintenance' => 'flagged']) }}" 
           class="px-6 py-2 rounded-[0.9rem] text-[10px] font-black uppercase tracking-widest transition-all duration-300 {{ request('maintenance') == 'flagged' ? 'bg-red-500 text-white shadow-md' : 'text-gray-400 hover:text-red-500' }}">
            Issues Only
        </a>
        <a href="{{ route('admin.feedbacks.index', ['maintenance' => 'none']) }}" 
           class="px-6 py-2 rounded-[0.9rem] text-[10px] font-black uppercase tracking-widest transition-all duration-300 {{ request('maintenance') == 'none' ? 'bg-green-500 text-white shadow-md' : 'text-gray-400 hover:text-green-500' }}">
            Good Condition
        </a>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100/80 text-[10px] text-gray-400 uppercase font-black tracking-widest">
                    <th class="px-10 py-6">Date</th>
                    <th class="px-10 py-6">Customer</th>
                    <th class="px-10 py-6">Vehicle</th>
                    <th class="px-10 py-6">Status</th>
                    <th class="px-10 py-6 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($feedbacks as $feedback)
                    <tr class="hover:bg-gray-50/60 transition-colors group cursor-pointer" onclick="window.location='{{ route('admin.bookings.show_detail', $feedback->booking->id) }}'">
                        <td class="px-10 py-7">
                            <span class="text-sm font-bold text-gray-800">{{ $feedback->created_at->format('d M Y') }}</span>
                            <span class="block text-[10px] text-gray-400 font-bold mt-1 tracking-tight">{{ $feedback->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="px-10 py-7">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center text-sm font-black border border-gray-100 group-hover:bg-white group-hover:shadow-sm transition-all">
                                    {{ substr($feedback->booking->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800 leading-none">{{ $feedback->booking->user->name ?? 'Unknown' }}</p>
                                    <p class="text-[11px] text-gray-400 mt-1.5 font-bold tracking-tight">{{ $feedback->booking->user->phone ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-7">
                            @if($feedback->booking->vehicle)
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('images/' . $feedback->booking->vehicle->vehicle_image) }}" alt="Car" class="h-10 w-16 object-contain p-1 bg-white rounded-xl border border-gray-100 shadow-sm opacity-90 group-hover:opacity-100 transition-opacity">
                                <div>
                                    <p class="text-sm font-black text-gray-800 leading-none">{{ $feedback->booking->vehicle->brand }} {{ $feedback->booking->vehicle->model }}</p>
                                    <span class="text-[9px] font-mono font-bold text-gray-400 tracking-wider bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100 mt-1.5 inline-block">{{ $feedback->booking->vehicle->plate_number }}</span>
                                </div>
                            </div>
                            @else
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-widest italic">Archived</span>
                            @endif
                        </td>
                        <td class="px-10 py-7">
                            @php
                                $ratings = $feedback->ratings ?? [];
                                $isNewStyle = false;
                                foreach($ratings as $k => $v) { if(strpos($k, 'issue_') === 0) { $isNewStyle = true; break; } }
                                
                                if ($isNewStyle) {
                                    $issuesMap = [
                                        'issue_interior' => 'Interior',
                                        'issue_smell' => 'Smell',
                                        'issue_mechanical' => 'Mech',
                                        'issue_ac' => 'A/C',
                                        'issue_exterior' => 'Ext',
                                        'issue_safety' => 'Safe',
                                    ];
                                    $reportedLabels = [];
                                    foreach($issuesMap as $key => $label) {
                                        if(isset($ratings[$key]) && $ratings[$key] === true) { $reportedLabels[] = $label; }
                                    }
                                    $hasIssues = count($reportedLabels) > 0;
                                } else {
                                    $numericRatings = array_filter($ratings, fn($val) => is_numeric($val) && $val > 0);
                                    $avg = count($numericRatings) > 0 ? array_sum($numericRatings) / count($numericRatings) : 0;
                                }
                            @endphp
                            
                            @if($isNewStyle)
                                <div class="flex flex-col gap-1.5">
                                    @if($hasIssues)
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 animate-pulse"></span>
                                            <span class="text-[9px] font-black text-red-600 uppercase tracking-widest">Maintenance Required</span>
                                        </div>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($reportedLabels as $label)
                                                <span class="px-1.5 py-0.5 bg-red-50 text-red-500 text-[8px] font-black rounded border border-red-100/50 uppercase">{{ $label }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                            <span class="text-[9px] font-black text-green-600 uppercase tracking-widest">Everything Perfect</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <i class="ri-star-fill text-[10px] text-yellow-400"></i>
                                    <span class="text-sm font-black text-gray-800">{{ number_format($avg, 1) }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-10 py-7 text-right">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 group-hover:text-[#cb5c55] transition-colors">View Details</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="ri-chat-smile-2-line text-3xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium text-sm">No feedback received yet.</p>
                                <p class="text-gray-400 text-xs mt-1">Feedback will appear here once customers return their vehicles.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($feedbacks->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $feedbacks->links() }}
        </div>
    @endif
</div>
@endsection
