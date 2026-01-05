<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Fetch ALL vehicles to display on landing page with their pricing tiers
        $vehicles = Vehicle::with('pricingTier.rules')->whereIn('status', ['Available', 'Rented'])->get();

        // Default availability for display purposes
        foreach ($vehicles as $vehicle) {
            $vehicle->is_available = true;
        }

        return view('welcome', compact('vehicles'));
    }
}