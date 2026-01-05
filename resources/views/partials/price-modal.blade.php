@props(['vehicle'])

<button 
    type="button"
    onclick="openPublicRateModal(this)"
    data-brand="{{ $vehicle->brand }}"
    data-model="{{ $vehicle->model }}"
    data-tier="{{ $vehicle->pricingTier ? $vehicle->pricingTier->name : '' }}"
    data-rates="{{ json_encode($vehicle->pricingTier ? $vehicle->pricingTier->rates : []) }}"
    data-price-per-hour="{{ $vehicle->price_per_hour }}"
    class="text-sm font-semibold text-[#ec5a29] hover:text-[#d14a1e] underline ml-2 transition-colors duration-200 inline-flex items-center gap-1 cursor-pointer"
>
    <i class="ri-price-tag-3-line"></i> Check Rates
</button>
