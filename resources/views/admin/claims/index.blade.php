@extends('layouts.admin')

@section('header_title', 'Review Staff Claims')

@section('content')
<div class="space-y-8">
    {{-- Header Section --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight uppercase">Claims Dashboard</h2>
            <p class="text-gray-500 text-sm font-medium mt-1">Review and process staff reimbursement requests</p>
        </div>
        <div class="flex gap-4">
            <div class="px-6 py-3 bg-blue-50 rounded-2xl border border-blue-100 text-center">
                <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Pending</p>
                <p class="text-xl font-black text-blue-600">{{ $claims->where('status', 'Pending')->count() }}</p>
            </div>
            <div class="px-6 py-3 bg-green-50 rounded-2xl border border-green-100 text-center">
                <p class="text-[10px] font-black text-green-400 uppercase tracking-widest">Approved</p>
                <p class="text-xl font-black text-green-600">{{ $claims->where('status', 'Approved')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Search Filters --}}
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.claims.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto flex-1">
                {{-- Date Filter --}}
                <div class="relative w-full sm:w-48">
                    <i class="ri-calendar-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="date" name="filter_date" value="{{ request('filter_date') }}" 
                        class="pl-11 pr-4 py-3 w-full bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-none focus:border-[#cb5c55] transition"
                        placeholder="Filter Date">
                </div>
                {{-- Staff Search --}}
                <div class="relative w-full sm:flex-1 max-w-md">
                    <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="staff_search" value="{{ request('staff_search') }}" 
                        class="pl-11 pr-4 py-3 w-full bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-none focus:border-[#cb5c55] transition uppercase"
                        placeholder="SEARCH BY STAFF NAME OR ID...">
                </div>
            </div>

            <div class="flex gap-3 w-full sm:w-auto">
                {{-- Filter Button --}}
                <button type="submit" class="bg-gray-900 text-white px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                    Filter
                </button>
                
                {{-- Clear Filter --}}
                @if(request('filter_date') || request('staff_search'))
                    <a href="{{ route('admin.claims.index') }}" class="px-6 py-3 bg-white border border-gray-200 text-gray-500 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-red-50 hover:text-red-500 hover:border-red-100 transition shadow-sm flex items-center justify-center">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Claims Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] uppercase text-gray-400 font-black tracking-widest border-b">
                    <tr>
                        <th class="px-8 py-5">Staff</th>
                        <th class="px-8 py-5">Type & Vehicle</th>
                        <th class="px-8 py-5 text-right">Amount</th>
                        <th class="px-8 py-5">Date & Status</th>
                        <th class="px-8 py-5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($claims as $claim)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-[#cb5c55] text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                    {{ substr($claim->user->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm leading-tight">{{ $claim->user->name ?? 'Unknown User' }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $claim->user->matric_staff_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="font-bold text-gray-800 text-sm leading-tight">{{ $claim->claim_type }}</p>
                            <p class="text-[10px] text-gray-400 font-mono mt-1">Car: {{ $claim->vehicle_plate }}</p>
                            
                            {{-- Staff Receipt (Always Visible) --}}
                            @if($claim->receipt_path)
                                <a href="{{ asset('storage/' . $claim->receipt_path) }}" target="_blank" class="inline-flex items-center gap-1 mt-2 text-[10px] font-black text-gray-500 uppercase tracking-widest hover:text-[#cb5c55] hover:underline transition">
                                    <i class="ri-attachment-line"></i> Staff Receipt
                                </a>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="text-base font-black text-gray-900">RM {{ number_format($claim->amount, 2) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-xs text-gray-500 font-bold">{{ $claim->claim_date_time->format('d M Y, h:i A') }}</p>
                            @php
                                $statusStyle = match($claim->status) {
                                    'Approved' => 'bg-green-100 text-green-700 border-green-200',
                                    'Rejected' => 'bg-red-100 text-red-700 border-red-200',
                                    default => 'bg-blue-100 text-blue-700 border-blue-200'
                                };
                            @endphp
                            <span class="inline-block mt-1 px-3 py-1 text-[9px] font-black rounded-full border {{ $statusStyle }} uppercase tracking-widest">
                                {{ $claim->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($claim->status === 'Pending')
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" class="bg-[#cb5c55] text-white text-[10px] font-black px-6 py-2.5 rounded-xl shadow-lg shadow-red-100 hover:bg-[#b04a45] transition uppercase tracking-widest">
                                        Process
                                    </button>

                                    {{-- Modal/Dropdown for Processing --}}
                                    <div x-show="open" @click.away="open = false" x-cloak
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-[2px] p-4 text-left">
                                        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all p-8">
                                            <h3 class="text-xl font-black text-gray-900 tracking-tight mb-2 uppercase">Process Claim #{{ $claim->id }}</h3>
                                            <p class="text-xs text-gray-500 mb-6 font-bold uppercase tracking-widest border-b pb-4">
                                                RM {{ number_format($claim->amount, 2) }} - {{ $claim->claim_type }}
                                            </p>

                                            <form action="{{ route('admin.claims.status', $claim->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="space-y-4">
                                                    <div class="space-y-2">
                                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</label>
                                                        <select name="status" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold text-gray-700 outline-none">
                                                            <option value="Approved">Approve Claim</option>
                                                            <option value="Rejected">Reject Claim</option>
                                                        </select>
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Bank Transfer Receipt (Compulsory)</label>
                                                        <input type="file" name="receipt" required accept="image/*"
                                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold text-gray-700 outline-none text-xs">
                                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">* Upload screenshot of the bank transfer</p>
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Remark (Optional)</label>
                                                        <textarea name="reason" placeholder="Add a note for the staff..." rows="3"
                                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold text-gray-700 outline-none resize-none"></textarea>
                                                    </div>
                                                    <div class="flex gap-4 pt-4">
                                                        <button type="button" @click="open = false" 
                                                            class="flex-1 bg-gray-100 text-gray-500 font-black py-4 rounded-xl uppercase tracking-widest text-xs hover:bg-gray-200 transition">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" 
                                                            class="flex-1 bg-[#cb5c55] text-white font-black py-4 rounded-xl shadow-lg shadow-red-200 uppercase tracking-widest text-xs hover:bg-[#b04a45] transition">
                                                            Confirm
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col items-start gap-1">
                                    {{-- Admin Payment Proof --}}
                                    @if($claim->payment_proof)
                                        <a href="{{ asset('storage/' . $claim->payment_proof) }}" target="_blank" 
                                           class="text-[10px] font-black text-[#cb5c55] uppercase tracking-widest hover:underline flex items-center gap-1 mb-1">
                                            <i class="ri-file-transfer-line"></i> View Payment Receipt
                                        </a>
                                    @endif

                                    @if($claim->action_reason)
                                        <p class="text-xs text-gray-600 leading-tight text-left">
                                            <span class="font-bold text-gray-800">Remarks:</span> {{Str::limit($claim->action_reason, 40)}}
                                        </p>
                                    @endif

                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter mt-1 text-left">By: {{ $claim->processor->name ?? 'System' }}</p>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-2">
                                    <i class="ri-search-2-line text-2xl text-gray-300"></i>
                                </div>
                                @if(request('filter_date') || request('staff_search'))
                                    <p class="text-gray-600 font-bold text-sm">No claims found matching your search.</p>
                                    <p class="text-xs text-gray-400">Try adjusting your filters or clearing them.</p>
                                    <a href="{{ route('admin.claims.index') }}" class="mt-2 text-[#cb5c55] text-xs font-black hover:underline uppercase tracking-widest">Clear All Filters</a>
                                @else
                                    <p class="text-gray-400 font-bold italic">No claims data available yet.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($claims->hasPages())
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
            {{ $claims->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
