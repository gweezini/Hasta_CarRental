<?php

namespace App\Services;

use App\Models\Vehicle;
use Carbon\Carbon;
use App\Models\PricingRule;

class PricingService
{
    public function calculatePrice(Vehicle $vehicle, Carbon $start, Carbon $end): array
    {
        // 1. Calculate duration hours (round up to next hour as efficient rental standard)
        // User said: "for 2 hours booking, follow the 3 hour rule". 
        // This implies we look for rules >= duration.
        // We need exact hours first.
        
        $minutes = $start->floatDiffInMinutes($end);
        $hours = ceil($minutes / 60);
        if ($hours < 1) $hours = 1;

        // 2. Check for Pricing Tier
        if (!$vehicle->pricing_tier_id) {
            // Fallback to legacy logic
            return [
                'subtotal' => $hours * $vehicle->price_per_hour,
                'hours' => $hours,
                'tier_name' => null,
                'rate_applied' => 'Standard Rate (RM ' . $vehicle->price_per_hour . '/hr)'
            ];
        }

        $tier = $vehicle->pricingTier;
        // Eager load rules if not loaded
        $tier->load('rules');
        $rules = $tier->rules->sortBy('hour_limit');

        // 3. Calculation Logic
        $fullDays = floor($hours / 24);
        $remainingHours = $hours % 24;

        $totalPrice = 0;

        // Add 24-hour blocks
        if ($fullDays > 0) {
            $rule24 = $rules->firstWhere('hour_limit', 24);
            if ($rule24) {
                $totalPrice += $fullDays * $rule24->price;
            } else {
                // Fallback if 24h rule missing (shouldn't happen with seeder)
                // Maybe take max rule or extrapolate? For now assume it exists.
                $maxRule = $rules->last(); 
                $totalPrice += $fullDays * ($maxRule ? $maxRule->price : 0);
            }
        }

        // Add remaining hours
        if ($remainingHours > 0) {
            // Find smallest rule >= remainingHours
            $rule = $rules->first(function ($rule) use ($remainingHours) {
                return $rule->hour_limit >= $remainingHours;
            });

            if ($rule) {
                $totalPrice += $rule->price;
            } else {
                // If remaining hours > max limit (e.g. max 12, but we have 18?)
                // User defined rules up to 24.
                // If we have remaining hours 13..23, and rules are 12, 24.
                // Then 24 hour rule should technically cover it?
                // Use case: "loop from the beginning" applies to > 25 hours.
                // So 1-24 is covered by standard rules.
                // If I have 15 hours, and rules are 12, 24. 
                // It should likely pick 24. 
                // The `first(>=)` logic handles this correctly (picks 24).
                
                // Fallback for safety
                 $maxRule = $rules->last();
                 if ($maxRule) $totalPrice += $maxRule->price;
            }
        }

        return [
            'hours' => $hours,
            'subtotal' => $totalPrice, // Renaming for consistency with controller
            'tier_name' => $tier->name,
            'rate_applied' => $tier->name . ' Rules'
        ];
    }
}
