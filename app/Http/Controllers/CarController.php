<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    public function index()
    {
        $vehicle = Vehicle::with('pricingTier')->get();
        return view('admin.vehicle.index', compact('vehicle'));
    }

    public function show($id)
    {
        $vehicle = Vehicle::with('maintenanceLogs')->findOrFail($id);
        return view('admin.vehicle.show', compact('vehicle'));
    } 

    public function create()
    {
        $types = DB::table('vehicle_types')->get();
        return view('admin.vehicle.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand'             => 'required|string',
            'model'             => 'required|string',
            'plate_number'      => 'required|string|unique:vehicles,plate_number',
            'year'              => 'required|numeric',
            'vehicle_id_custom' => 'required|string|unique:vehicles,vehicle_id_custom',
            'type_id'           => 'required|numeric',
            'capacity'          => 'required|numeric',
            'is_hasta_owned'    => 'required|boolean',
            'current_fuel_bars' => 'required|numeric|min:0|max:10',
            'road_tax_expiry'   => 'nullable|date',
            'insurance_expiry'  => 'nullable|date',
            'price_per_hour'    => 'required|numeric',
            'status'            => 'required|string',
            'vehicle_image'     => 'required|image|max:2048', // 创建时还是必须要有图片的
        ]);

        if ($request->hasFile('vehicle_image')) {
            $data['vehicle_image'] = $request->file('vehicle_image')->store('vehicles', 'public');
        }
        Vehicle::create($data);
        return redirect()->route('admin.vehicle.index')->with('success', 'New vehicle added successfully!');
    }

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $types = DB::table('vehicle_types')->get();
        return view('admin.vehicle.edit', compact('vehicle', 'types'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $data = $request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'plate_number' => 'required|string',
            'year' => 'required|numeric',
            'vehicle_id_custom' => 'required|string',
            'type_id' => 'required|numeric',
            'capacity' => 'required|numeric',
            'is_hasta_owned' => 'required|boolean',
            'current_fuel_bars' => 'required|numeric|min:0|max:10',
            'road_tax_expiry' => 'required|date',
            'insurance_expiry' => 'required|date',
            'status' => 'required|string',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pricing_tier_id' => 'nullable|exists:pricing_tiers,id',
        ]);

        if (!$request->hasFile('vehicle_image')) {
            unset($data['vehicle_image']);
        }

        if ($request->hasFile('vehicle_image')) {
            if ($vehicle->vehicle_image) {
                Storage::disk('public')->delete($vehicle->vehicle_image);
            }
            
            $imagePath = $request->file('vehicle_image')->store('vehicles', 'public');
            $data['vehicle_image'] = $imagePath;
        }

        $vehicle->update($data);

        // Update Pricing Rates if present
        if ($request->has('rates') && $vehicle->pricingTier) {
            $ratesData = $request->validate([
                'rates' => 'array',
                'rates.*' => 'numeric|min:0',
            ])['rates'];

            foreach ($ratesData as $hour => $price) {
                // Lookup by tier ID and hour limit since the input name is rates[hour]
                $rate = \App\Models\PricingRate::where('pricing_tier_id', $vehicle->pricing_tier_id)
                            ->where('hour_limit', $hour)
                            ->first();
                
                if ($rate) {
                    $rate->update(['price' => $price]);
                } else {
                    // Create if it doesn't exist (robustness)
                    \App\Models\PricingRate::create([
                        'pricing_tier_id' => $vehicle->pricing_tier_id,
                        'hour_limit' => $hour,
                        'price' => $price
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Vehicle and pricing updated successfully!');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        if ($vehicle->vehicle_image) {
            Storage::disk('public')->delete($vehicle->vehicle_image);
        }

        $vehicle->delete();
        return redirect()->route('admin.vehicle.index')->with('success', 'Vehicle deleted successfully!');
    }

    public function storeMaintenance(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'cost' => 'required|numeric|min:0',
        ]);

        \App\Models\MaintenanceLog::create([
            'vehicle_id' => $id,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'description' => $request->description,
            'date' => $request->date,
            'cost' => $request->cost,
        ]);

        return redirect()->back()->with('success', 'Maintenance record added successfully!');
    }
}