@extends('layouts.admin')

@section('header_title', 'Submit Claim')

@section('content')
<div class="max-w-3xl mx-auto">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Header Decoration --}}
        <div class="bg-[#cb5c55] p-8 text-center text-white">
            <h1 class="text-2xl font-black tracking-tight uppercase">CLAIM HASTA TRAVEL & TOURS SDN BHD</h1>
            <p class="text-white/70 text-sm mt-1 font-bold">Official Staff Reimbursement Form</p>
        </div>

        <form action="{{ route('admin.claims.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            {{-- 1. Full Name --}}
            <div class="space-y-2">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Full Name</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="ri-user-smile-line"></i>
                    </span>
                    <input type="text" name="name_display" value="{{ Auth::user()->name }}" disabled
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 font-bold focus:ring-0 cursor-not-allowed">
                </div>
                <p class="text-[10px] text-gray-400 font-medium italic">* Logged in as {{ Auth::user()->name }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- 2. Claim Type --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Type of Claim</label>
                    <select name="claim_type" required
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-bold focus:ring-2 focus:ring-[#cb5c55]/20 focus:border-[#cb5c55] transition outline-none">
                        <option value="" disabled selected>Select category</option>
                        <option value="Fuel">Fuel</option>
                        <option value="Car Wash">Car Wash</option>
                        <option value="Delivery PIC">Delivery PIC</option>
                        <option value="Follow Job">Follow Job</option>
                        <option value="Extra Job (By Task)">Extra Job (By Task)</option>
                        <option value="Service & Maintenance">Service & Maintenance</option>
                        <option value="Office Equipments">Office Equipments</option>
                        <option value="Summon">Summon</option>
                        <option value="Booth">Booth</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                {{-- 3. Vehicle Applied To --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Car Involved</label>
                    <select name="vehicle_plate" required
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-bold focus:ring-2 focus:ring-[#cb5c55]/20 focus:border-[#cb5c55] transition outline-none">
                        <option value="" disabled selected>Select Plate No.</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->plate_number }}">{{ $vehicle->plate_number }} ({{ $vehicle->model }})</option>
                        @endforeach
                        <option value="Other">Other / Not Listed</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- 4. Date and Time --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Date & Time</label>
                    <div class="flex gap-2">
                        <input type="date" name="claim_date" required value="{{ date('Y-m-d') }}"
                            class="flex-1 px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-bold focus:ring-2 focus:ring-[#cb5c55]/20 focus:border-[#cb5c55] transition outline-none">
                        <input type="time" name="claim_time" required value="{{ date('H:i') }}"
                            class="w-32 px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-bold focus:ring-2 focus:ring-[#cb5c55]/20 focus:border-[#cb5c55] transition outline-none">
                    </div>
                </div>

                {{-- 5. Amount --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Claim Amount (RM)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-bold">RM</span>
                        <input type="number" name="amount" required step="0.01" min="0" placeholder="0.00"
                            class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-bold focus:ring-2 focus:ring-[#cb5c55]/20 focus:border-[#cb5c55] transition outline-none">
                    </div>
                </div>
            </div>

            {{-- 6. Extra Description --}}
            <div class="space-y-2">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Additional Notes</label>
                <textarea name="description" rows="4" placeholder="Briefly explain the claim context..."
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-bold focus:ring-2 focus:ring-[#cb5c55]/20 focus:border-[#cb5c55] transition outline-none resize-none"></textarea>
            </div>

            {{-- Submit Button --}}
            <div class="pt-4">
                <button type="submit" 
                    class="w-full bg-[#cb5c55] text-white font-black py-4 rounded-xl shadow-lg shadow-red-200 hover:bg-[#b04a45] transition transform active:scale-[0.98] uppercase tracking-widest">
                    Submit Claim Request
                </button>
                <p class="text-center text-[10px] text-gray-400 mt-4 uppercase font-bold tracking-tighter">
                    Submission will be reviewed by top management
                </p>
            </div>
        </form>
    {{-- Previous Claims History --}}
    <div class="mt-12 bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-8 py-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">My Previous Claims</h2>
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $myClaims->count() }} total</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50 text-[10px] uppercase text-gray-400 font-black tracking-widest border-b">
                    <tr>
                        <th class="px-8 py-4">Ref #</th>
                        <th class="px-8 py-4">Type</th>
                        <th class="px-8 py-4 text-right">Amount</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-center">Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($myClaims as $claim)
                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase">#{{ $claim->id }}</td>
                        <td class="px-8 py-5">
                            <p class="font-bold text-gray-900 text-sm leading-tight">{{ $claim->claim_type }}</p>
                            <p class="text-[10px] text-gray-400 font-mono mt-1">{{ $claim->vehicle_plate }}</p>
                        </td>
                        <td class="px-8 py-5 text-right font-black text-gray-900 text-sm">RM {{ number_format($claim->amount, 2) }}</td>
                        <td class="px-8 py-5">
                            @php
                                $statusStyle = match($claim->status) {
                                    'Approved' => 'bg-green-100 text-green-700 border-green-200',
                                    'Rejected' => 'bg-red-100 text-red-700 border-red-200',
                                    default => 'bg-blue-100 text-blue-700 border-blue-200'
                                };
                            @endphp
                            <span class="px-3 py-1 text-[9px] font-black rounded-full border {{ $statusStyle }} uppercase tracking-widest">
                                {{ $claim->status }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($claim->receipt_path)
                                <a href="{{ asset('storage/' . $claim->receipt_path) }}" target="_blank" 
                                    class="inline-flex items-center gap-1.5 text-[10px] font-black text-[#cb5c55] uppercase tracking-widest hover:underline">
                                    <i class="ri-file-list-3-line text-sm"></i> View Receipt
                                </a>
                            @else
                                <span class="text-[10px] text-gray-300 font-bold uppercase tracking-widest italic">No Receipt</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-10 text-center text-gray-400 font-bold italic text-sm">No claim history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
