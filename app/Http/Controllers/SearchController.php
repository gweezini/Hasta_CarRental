<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get Inputs
        $location = $request->input('location');
        $startDate = $request->input('start_date');
        $startTime = $request->input('start_time');
        $stopDate = $request->input('stop_date');
        $stopTime = $request->input('stop_time');

        // 2. Validate Inputs (Basic)
        if (!$startDate || !$startTime || !$stopDate || !$stopTime) {
            return redirect()->back()->with('error', 'Please provide both pick-up and return dates and times.');
        }

        try {
            $start = Carbon::parse("$startDate $startTime");
            $end = Carbon::parse("$stopDate $stopTime");

            // 3. specific validation rules
            if ($start->diffInHours($end) < 1) { // Min 1 hour
               return redirect()->back()->withInput()->with('error', 'Minimum rental duration is 1 hour.');
            }
             if ($end->lessThanOrEqualTo($start)) {
                return redirect()->back()->withInput()->with('error', 'Return time must be after pickup time.');
            }
             if ($start->isPast()) {
                 // Allow small buffer or just strict future? Let's be strict for new bookings
                 if ($start->diffInMinutes(now()) > 15 && $start->lt(now())) {
                     return redirect()->back()->withInput()->with('error', 'Pickup time cannot be in the past.');
                 }
            }


            // 4. Get All Vehicles (Active ones)
            $allVehicles = Vehicle::with('pricingTier.rates')->whereIn('status', ['Available', 'Rented'])->get();
            
            $availableVehicles = collect();
            $unavailableVehicles = collect();

            foreach ($allVehicles as $vehicle) {
                // Check conflicts
                $hasConflict = $vehicle->bookings()
                    ->whereIn('status', ['Pending', 'Waiting for Verification', 'Verify Receipt', 'Approved', 'Rented'])
                    ->where(function ($q) use ($start, $end) {
                        // Overlap logic: (StartA < EndB) and (EndA > StartB)
                        $q->where('pickup_date_time', '<', $end)
                          ->where('return_date_time', '>', $start);
                    })
                    ->exists();

                if ($hasConflict) {
                     $unavailableVehicles->push($vehicle);
                } else {
                     $availableVehicles->push($vehicle);
                }
            }

            return view('search.results', compact('availableVehicles', 'unavailableVehicles', 'location', 'start', 'end'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid date or time format.');
        }
    }
}
