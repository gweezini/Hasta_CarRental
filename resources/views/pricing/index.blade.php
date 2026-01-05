@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-6 text-gray-800">Vehicle Pricing Rates</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($tiers as $tier)
                    <div class="border rounded-lg shadow-md p-4 bg-gray-50 hover:shadow-lg transition">
                        <h2 class="text-xl font-semibold mb-3 text-indigo-600">{{ $tier->name }}</h2>
                        <ul class="text-sm text-gray-600 space-y-2">
                            @foreach($tier->rules->sortBy('hour_limit') as $rule)
                                <li class="flex justify-between border-b pb-1 last:border-0">
                                    <span>{{ $rule->hour_limit }} Hour{{ $rule->hour_limit > 1 ? 's' : '' }}</span>
                                    <span class="font-bold">RM {{ number_format($rule->price, 0) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
