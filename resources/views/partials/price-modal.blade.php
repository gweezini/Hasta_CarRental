
<!-- Partial: Price Modal -->
<!-- Requires $vehicle object with loaded pricingTier.rates -->

<div x-data="{ showPriceModal: false }" class="inline-block">
    <button @click="showPriceModal = true" type="button" class="text-sm text-primary underline hover:text-primary-dark ml-2">
        See Rates
    </button>

    <!-- Modal Backdrop -->
    <div x-show="showPriceModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-[2000] flex items-center justify-center p-4">

        <!-- Modal Content -->
        <div @click.away="showPriceModal = false" 
             class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden relative"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90">

            <!-- Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">
                    {{ $vehicle->brand }} {{ $vehicle->model }} Rates
                </h3>
                <button @click="showPriceModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                @if($vehicle->pricingTier)
                    <div class="mb-4">
                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                            {{ $vehicle->pricingTier->name }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Duration (Hours)</th>
                                    <th scope="col" class="px-4 py-3 text-right">Price (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicle->pricingTier->rates->sortBy('hour_limit') as $rate)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900">
                                            {{ $rate->hour_limit == 24 ? '24 Hours (1 Day)' : $rate->hour_limit . ' Hours' }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-primary">
                                            {{ number_format($rate->price, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-xs text-gray-500 bg-gray-50 p-3 rounded">
                        <p class="mb-1"><i class="ri-information-line"></i> <strong>Note:</strong></p>
                        <ul class="list-disc list-inside">
                            <li>Prices are cumulative for longer durations.</li>
                            <li>Example: 25 hours = 24h Price + 1h Price.</li>
                        </ul>
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500">
                        <p>Standard Rate: <span class="font-bold text-primary">RM {{ number_format($vehicle->price_per_hour, 2) }}</span> / hour</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .text-primary { color: #ec5a29; }
    .bg-primary { background-color: #ec5a29; }
    .hover\:text-primary-dark:hover { color: #d14a1e; }
</style>
