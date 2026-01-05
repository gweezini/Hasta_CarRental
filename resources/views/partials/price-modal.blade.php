@props(['vehicle'])

<button 
    type="button"
    onclick="event.preventDefault(); event.stopPropagation(); openPublicRateModal(this)"
    data-brand="{{ $vehicle->brand }}"
    data-model="{{ $vehicle->model }}"
    data-tier="{{ $vehicle->pricingTier ? $vehicle->pricingTier->name : '' }}"
    data-rates="{{ json_encode($vehicle->pricingTier ? $vehicle->pricingTier->rules : []) }}"
    data-price-per-hour="{{ $vehicle->price_per_hour }}"
    style="background-color: #fff7ed; color: #ec5a29; padding: 4px 12px; border-radius: 9999px; border: 1px solid rgba(236, 90, 41, 0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;"
    onmouseover="this.style.backgroundColor='#ec5a29'; this.style.color='white';"
    onmouseout="this.style.backgroundColor='#fff7ed'; this.style.color='#ec5a29';"
    class="ml-2 hover:shadow-md cursor-pointer"
>
    <i class="ri-price-tag-3-fill"></i> View Rates
</button>
