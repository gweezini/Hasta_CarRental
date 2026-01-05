<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rental Prices') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($tiers as $tier)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-2">{{ $tier->name }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ $tier->description }}</p>
                        
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="text-left">Hours</th>
                                    <th class="text-right">Price (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tier->rates->sortBy('hour_limit') as $rate)
                                    <tr>
                                        <td class="py-1">{{ $rate->hour_limit }} Hours</td>
                                        <td class="text-right py-1">{{ number_format($rate->price, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                 <h3 class="text-lg font-bold mb-2">Important Notes</h3>
                 <ul class="list-disc list-inside text-sm text-gray-700">
                     <li>For bookings more than 24 hours, the 25th hour counts as the first hour of a new cycle.</li>
                     <li>Rentals are charged based on the next available hourly block (e.g. 2 hours charged as 3 hours).</li>
                 </ul>
            </div>
        </div>
    </div>
</x-app-layout>
