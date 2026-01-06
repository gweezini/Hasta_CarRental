@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pricing Management</h1>
        <a href="{{ route('admin.pricing.create') }}" class="inline-flex items-center px-4 py-2 bg-[#cb5c55] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#b04a44] active:bg-[#963f3a] focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
            <i class="ri-add-line mr-2 text-lg"></i> Create New Tier
        </a>
    </div>

    @if($tiers->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="h-20 w-20 bg-red-50 text-[#cb5c55] rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-price-tag-3-line text-4xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">No Pricing Tiers Found</h3>
            <p class="text-gray-500 mb-6">Get started by creating your first vehicle pricing tier.</p>
            <a href="{{ route('admin.pricing.create') }}" class="inline-flex items-center px-4 py-2 bg-[#cb5c55] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#b04a44] transition shadow-sm">
                Create Tier
            </a>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($tiers as $tier)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-200">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">{{ $tier->name }}</h3>
                        <p class="text-sm text-gray-500 font-medium mt-1">
                            Vehicles: {{ $tier->vehicles()->count() }}
                        </p>
                    </div>
                    <div class="h-10 w-10 bg-red-50 text-[#cb5c55] rounded-lg flex items-center justify-center">
                        <i class="ri-price-tag-3-line text-xl"></i>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Current Rates</h4>
                    <ul class="space-y-2 text-sm">
                        @foreach($tier->rules->sortBy('hour_limit') as $rule)
                            <li class="flex justify-between items-center py-1 border-b border-gray-50 last:border-0 hover:bg-gray-50 px-2 -mx-2 rounded transition">
                                <span class="text-gray-600 font-medium">{{ $rule->hour_limit }} Hour(s)</span>
                                <span class="font-bold text-gray-800">RM {{ number_format($rule->price, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-6">
                    <a href="{{ route('admin.pricing.edit', $tier->id) }}" class="block w-full text-center py-2.5 bg-[#cb5c55] text-white font-bold text-sm rounded-lg hover:bg-[#b04a44] transition shadow-sm">
                        Edit Prices
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
