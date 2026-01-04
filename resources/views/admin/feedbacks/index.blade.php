@extends('layouts.admin')

@section('header_title', 'Customer Feedbacks')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100/80 text-xs text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">Date</th>
                    <th class="px-6 py-4 font-semibold">Customer</th>
                    <th class="px-6 py-4 font-semibold">Vehicle</th>
                    <th class="px-6 py-4 font-semibold">Rating</th>
                    <th class="px-6 py-4 font-semibold">Experience</th>
                    <th class="px-6 py-4 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($feedbacks as $feedback)
                    <tr class="hover:bg-gray-50/60 transition duration-200">
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-700">{{ $feedback->created_at->format('d M Y') }}</span>
                            <span class="block text-[10px] text-gray-400 font-medium">{{ $feedback->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xs font-bold border border-blue-100">
                                    {{ substr($feedback->booking->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $feedback->booking->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-400 font-medium">{{ $feedback->booking->user->phone ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($feedback->booking->vehicle)
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('images/' . $feedback->booking->vehicle->vehicle_image) }}" alt="Car" class="h-8 w-12 object-cover rounded shadow-sm border border-gray-100">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $feedback->booking->vehicle->brand }} {{ $feedback->booking->vehicle->model }}</p>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500 uppercase tracking-wide border border-gray-200">{{ $feedback->booking->vehicle->plate_number }}</span>
                                </div>
                            </div>
                            @else
                                <span class="text-gray-400 italic">Vehicle Deleted</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $ratings = $feedback->ratings ?? [];
                                $numericRatings = array_filter($ratings, function($val) {
                                    return is_numeric($val) && $val > 0;
                                });
                                $avg = count($numericRatings) > 0 ? array_sum($numericRatings) / count($numericRatings) : 0;
                            @endphp
                            <div class="flex items-center space-x-1" title="Average: {{ number_format($avg, 1) }}">
                                <i class="ri-star-fill text-yellow-400 text-base"></i>
                                <span class="text-sm font-bold text-gray-800">{{ number_format($avg, 1) }}</span>
                                <span class="text-xs text-gray-400 font-medium">/ 5</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 w-1/3">
                            <div x-data="{ expanded: false }">
                                <p class="text-sm text-gray-600 leading-relaxed" :class="{'line-clamp-2': !expanded}">
                                    {{ $feedback->description ?: 'No written feedback.' }}
                                </p>
                                @if(strlen($feedback->description) > 50)
                                    <button @click="expanded = !expanded" class="text-[10px] text-[#cb5c55] hover:underline font-bold mt-1 uppercase tracking-wider focus:outline-none">
                                        <span x-text="expanded ? 'Show Less' : 'Read More'"></span>
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($feedback->booking)
                                <a href="{{ route('admin.bookings.show_detail', $feedback->booking->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#cb5c55] text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm hover:bg-[#b04a44] transition">
                                    <i class="ri-file-list-3-fill"></i> View Details
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
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
