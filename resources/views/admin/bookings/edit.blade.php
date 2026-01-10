@extends('layouts.admin')

@section('header_title', 'Modify Booking #' . $booking->id)

@section('content')
{{-- Breadcrumbs --}}
<div class="mb-4 text-left">
    <a href="{{ route('admin.bookings.show_detail', $booking->id) }}" class="text-sm font-bold text-gray-400 hover:text-[#cb5c55] transition flex items-center gap-1.5 group">
        <i class="ri-arrow-left-line group-hover:-translate-x-1 transition-transform"></i> Back to Review
    </a>
</div>

<div class="max-w-xl mx-auto py-4">
    <div class="bg-white p-8 rounded-[1.75rem] shadow-xl shadow-gray-100 border border-gray-100 text-left">
        {{-- Header --}}
        <div class="mb-6 flex items-center gap-4 border-b border-gray-50 pb-5">
            <div class="h-12 w-12 bg-red-50 text-[#cb5c55] rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-edit-2-line text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-gray-800 tracking-tight">Modify Booking</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Assignment #{{ $booking->id }}</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-2 rounded-xl mb-5">
                <ul class="list-disc pl-5 text-[10px] font-black space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" class="space-y-5"
            x-data="{
                initialVehicle: '{{ $booking->vehicle_id }}',
                initialPickup: '{{ \Carbon\Carbon::parse($booking->pickup_date_time)->format('Y-m-d\TH:i') }}',
                initialReturn: '{{ \Carbon\Carbon::parse($booking->return_date_time)->format('Y-m-d\TH:i') }}',
                currentVehicle: '{{ $booking->vehicle_id }}',
                currentPickup: '{{ \Carbon\Carbon::parse($booking->pickup_date_time)->format('Y-m-d\TH:i') }}',
                currentReturn: '{{ \Carbon\Carbon::parse($booking->return_date_time)->format('Y-m-d\TH:i') }}',
                get hasChanges() {
                    return this.currentVehicle != this.initialVehicle || 
                           this.currentPickup != this.initialPickup || 
                           this.currentReturn != this.initialReturn;
                }
            }">
            @csrf
            @method('PUT')

            {{-- Vehicle Selection --}}
            <div class="space-y-1.5">
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 ml-1">New Car assignment</label>
                <div class="relative group">
                    <select name="vehicle_id" x-model="currentVehicle"
                            class="w-full bg-gray-50 border-2 border-transparent rounded-[1rem] px-5 py-3 text-gray-800 font-bold focus:bg-white focus:border-[#cb5c55] outline-none transition-all appearance-none cursor-pointer h-14 text-sm">
                        @foreach($vehicles->groupBy(fn($v) => $v->brand . ' ' . $v->model) as $modelGroup => $items)
                            <optgroup label="{{ $modelGroup }}" class="font-black text-gray-400 uppercase text-[9px] py-1">
                                @foreach($items as $vehicle)
                                    @php
                                        $price = $vehicle->pricingTier && $vehicle->pricingTier->rules->isNotEmpty()
                                            ? ($vehicle->pricingTier->rules->where('hour_limit', 1)->first()->price ?? $vehicle->pricingTier->rules->min('price'))
                                            : $vehicle->price_per_hour;
                                    @endphp
                                    <option value="{{ $vehicle->id }}" {{ $booking->vehicle_id == $vehicle->id ? 'selected' : '' }} class="text-gray-800 font-bold text-sm">
                                        {{ $vehicle->plate_number }} — RM {{ number_format($price, 2) }}/hr
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 group-hover:text-[#cb5c55] transition-colors">
                        <i class="ri-arrow-down-s-line text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Schedule --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 ml-1">Pickup</label>
                    <input type="datetime-local" name="pickup_date_time" x-model="currentPickup"
                           class="w-full bg-gray-50 border-2 border-transparent rounded-[1rem] px-4 py-3 text-gray-800 font-bold focus:bg-white focus:border-[#cb5c55] outline-none transition-all text-xs h-14">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 ml-1">Return</label>
                    <input type="datetime-local" name="return_date_time" x-model="currentReturn"
                           class="w-full bg-gray-50 border-2 border-transparent rounded-[1rem] px-4 py-3 text-gray-800 font-bold focus:bg-white focus:border-[#cb5c55] outline-none transition-all text-xs h-14">
                </div>
            </div>

            {{-- Reason --}}
            <div class="space-y-1.5 pt-1">
                <label class="block text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 ml-1">Modification Reason</label>
                <textarea name="change_reason" rows="2" placeholder="Explain the change..."
                          class="w-full bg-gray-50 border-2 border-transparent rounded-[1rem] px-5 py-3 text-gray-800 font-bold focus:bg-white focus:border-[#cb5c55] outline-none transition-all resize-none text-[13px]" required></textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <button type="submit" 
                        :disabled="!hasChanges"
                        :class="hasChanges ? 'bg-[#cb5c55] hover:bg-[#b04a44] shadow-red-50' : 'bg-gray-200 cursor-not-allowed text-gray-400'"
                        class="sm:px-10 text-white font-bold py-3.5 rounded-xl shadow-lg transition-all transform flex items-center justify-center gap-2 uppercase tracking-widest text-[11px]"
                        :class="hasChanges && 'hover:scale-[1.01]'">
                    <i class="ri-check-line text-base"></i> Apply Changes
                </button>
                <a href="{{ route('admin.bookings.show_detail', $booking->id) }}" class="sm:px-10 bg-gray-50 hover:bg-gray-100 text-gray-400 font-bold py-3.5 rounded-xl transition-all uppercase tracking-widest text-[11px] flex items-center justify-center border border-gray-100">
                    Dismiss
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    select optgroup { font-style: normal; font-weight: 800; color: #9ca3af; background: #f9fafb; font-size: 10px; padding: 8px 0; }
    select option { font-weight: 700; color: #1f2937; padding: 12px; background: white; font-size: 16px; }
</style>
@endsection
