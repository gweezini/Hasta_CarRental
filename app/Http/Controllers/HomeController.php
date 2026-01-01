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

        $vehicles = Vehicle::query();

        if ($startDate && $startTime && $stopDate && $stopTime) {
            
            $start = Carbon::parse("$startDate $startTime");
            $end   = Carbon::parse("$stopDate $stopTime");

            $vehicles->whereDoesntHave('bookings', function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    
                    $q->where('pickup_date_time', '<', $end)
                      ->where('return_date_time', '>', $start);
                })

                ->whereIn('status', ['Pending', 'Waiting for Verification', 'Approved']);
            });
        }

        $vehicles->whereIn('status', ['Available', 'Rented']);

        $vehicles = $vehicles->get();

        return view('welcome', compact('vehicles'));
    }
}