<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $startTime = $request->input('start_time');
        $stopDate  = $request->input('stop_date');
        $stopTime  = $request->input('stop_time');

        // Fetch ALL vehicles first (filter only by status if needed, but we want to show 'Rented' ones too if they are just booked for specific dates)
        // Actually, we usually want 'Available' or 'Rented' status vehicles to be physically present in the fleet.
        $vehicles = Vehicle::whereIn('status', ['Available', 'Rented'])->get();

        // Default availability to true if no search parameters
        foreach ($vehicles as $vehicle) {
            $vehicle->is_available = true;
        }

        if ($startDate && $startTime && $stopDate && $stopTime) {
            try {
                $start = Carbon::parse("$startDate $startTime");
                $end   = Carbon::parse("$stopDate $stopTime");

                foreach ($vehicles as $vehicle) {
                    // Check for overlapping bookings
                    $hasConflict = $vehicle->bookings()
                        ->whereIn('status', ['Pending', 'Waiting for Verification', 'Approved'])
                        ->where(function ($q) use ($start, $end) {
                            $q->where('pickup_date_time', '<', $end)
                              ->where('return_date_time', '>', $start);
                        })
                        ->exists();

                    if ($hasConflict) {
                        $vehicle->is_available = false;
                    }
                }
            } catch (\Exception $e) {
                // If parsing fails, default to available or handle error
            }
        }

        return view('welcome', compact('vehicles'));
    }
}