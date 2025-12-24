<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Carbon\Carbon;

class BookingController extends Controller
{
    // This is the "show" method the error is looking for!
    public function show($id, Request $request)
    {
        $vehicle = Vehicle::findOrFail($id);

        $start = $request->query('start_date') . ' ' . $request->query('start_time');
        $stop = $request->query('stop_date') . ' ' . $request->query('stop_time');

        $startTime = Carbon::parse($start);
        $stopTime = Carbon::parse($stop);
        
        $hours = $startTime->diffInHours($stopTime);
        if ($hours <= 0) $hours = 1; 

        $totalPrice = $hours * $vehicle->price_per_hour;

        return view('booking', compact('vehicle', 'hours', 'totalPrice', 'start', 'stop'));
    }
}