<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\PricingTier;
use Carbon\Carbon;

class PricingService
{
    /**
     * Calculate price for a vehicle based on start and end times.
     * 
     * @param Vehicle $vehicle
     * @param Carbon $start
     * @param Carbon $end
     * @return array
     */
    public function calculatePrice(Vehicle $vehicle, Carbon $start, Carbon $end)
    {
        // 1. Calculate duration
        $hours = ceil($start->floatDiffInHours($end));
        if ($hours < 1) $hours = 1;

        // If vehicle has no pricing tier, fallback to old logic
        if (!$vehicle->pricing_tier_id) {
            return [
                'hours' => $hours,
                'subtotal' => $hours * $vehicle->price_per_hour,
                'tier_name' => 'Hourly (Legacy)',
                'rate_applied' => $vehicle->price_per_hour . '/hr'
            ];
        }

        // 2. Load Pricing Tier
        $tier = $vehicle->pricingTier;
        // Sort rates by hour_limit asc
        $rates = $tier->rates->sortBy('hour_limit');
        
        $rate24 = $rates->where('hour_limit', 24)->first();
        
        // If no 24h rate defined, fallback (safety check)
        if (!$rate24) {
             return [
                'hours' => $hours,
                'subtotal' => $hours * $vehicle->price_per_hour, // fallback
                 'tier_name' => 'Error: No 24h rate',
                'rate_applied' => 'Fallback'
            ];
        }

        // 3. Logic: Days + Remainder
        // 25h = 24h + 1h
        $days = floor($hours / 24);
        $remainder = $hours % 24;
        
        $subtotal = $days * $rate24->price;
        $remainderPrice = 0;
        $rateApplied = "";

        if ($remainder > 0) {
            // Find smallest tier >= remainder
            $foundRate = $rates->first(function($rate) use ($remainder) {
                return $rate->hour_limit >= $remainder;
            });
            
            // If remainder > largest defined tier (but < 24), defaults to 24h rate
            // Example: 13h, but max tier is 12h. Next is 24h.
            if (!$foundRate) {
                $foundRate = $rate24;
            }
            
            $remainderPrice = $foundRate->price;
            $rateApplied = "{$days} days @ RM{$rate24->price} + {$remainder}h (Tier {$foundRate->hour_limit}h @ RM{$foundRate->price})";
        } else {
             $rateApplied = "{$days} days @ RM{$rate24->price}";
        }

        $subtotal += $remainderPrice;

        return [
            'hours' => $hours,
            'subtotal' => $subtotal,
            'tier_name' => $tier->name,
            'rate_applied' => $rateApplied
        ];
    }
}
