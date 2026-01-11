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
        // Auto-update expired vehicles to Unavailable
        Vehicle::where('status', 'Available')
            ->where(function($q) {
                $q->where('road_tax_expiry', '<', now())
                  ->orWhere('insurance_expiry', '<', now());
            })
            ->update(['status' => 'Unavailable']);

        $query = Vehicle::with('pricingTier');
        
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }

        $vehicle = $query->get();
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
            'owner_name'        => 'nullable|string|max:255',
            'owner_ic_path'      => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'owner_license_path' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'geran_path'         => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'insurance_cover_path' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'chassis_number' => 'nullable|string|max:255',
            'engine_number' => 'nullable|string|max:255',
            'owner_ic_number' => 'nullable|string|max:50',
            'insurance_policy_number' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('vehicle_image')) {
            $data['vehicle_image'] = $request->file('vehicle_image')->store('vehicles', 'public');
        }

        // Handle Ownership Documents
        $docFields = ['owner_ic_path', 'owner_license_path', 'geran_path', 'insurance_cover_path'];
        foreach ($docFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('vehicle_docs', 'public');
            }
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
            'owner_name'        => 'nullable|string|max:255',
            'owner_ic_path'      => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'owner_license_path' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'geran_path'         => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'insurance_cover_path' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'chassis_number' => 'nullable|string|max:255',
            'engine_number' => 'nullable|string|max:255',
            'owner_ic_number' => 'nullable|string|max:50',
            'insurance_policy_number' => 'nullable|string|max:255',
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

        // Handle Ownership Documents Update
        $docFields = ['owner_ic_path', 'owner_license_path', 'geran_path', 'insurance_cover_path'];
        foreach ($docFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($vehicle->$field) {
                    Storage::disk('public')->delete($vehicle->$field);
                }
                $data[$field] = $request->file($field)->store('vehicle_docs', 'public');
            } else {
                unset($data[$field]); // Do not overwrite with null if no new file uploaded
            }
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
                // FIX: Use PricingRule model instead of PricingRate
                $rate = \App\Models\PricingRule::where('pricing_tier_id', $vehicle->pricing_tier_id)
                            ->where('hour_limit', $hour)
                            ->first();
                
                if ($rate) {
                    $rate->update(['price' => $price]);
                } else {
                    // Create if it doesn't exist (robustness)
                    \App\Models\PricingRule::create([
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

    public function availability(Request $request)
    {
        $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->input('start_date'))->startOfDay() : now()->subDays(3)->startOfDay();
        $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->input('end_date'))->endOfDay() : now()->addDays(6)->endOfDay();
        $typeId = $request->input('type_id');

        $query = Vehicle::query();

        if ($typeId) {
            $query->where('type_id', $typeId);
        }

        if ($request->filled('plate_number')) {
            $query->where('plate_number', 'like', '%' . $request->input('plate_number') . '%');
        }

        // Eager load bookings that overlap with the selected range
        $query->with(['bookings' => function($q) use ($startDate, $endDate) {
            $q->whereIn('status', ['Approved', 'Rented', 'Waiting for Verification', 'Completed'])
              ->where('pickup_date_time', '<=', $endDate)
              ->where('return_date_time', '>=', $startDate);
        }]);

        $vehicles = $query->get();
        $types = DB::table('vehicle_types')->get();

        // Generate date array for the view header
        $dates = [];
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dates[] = $current->copy();
            $current->addDay();
        }

        return view('admin.vehicle.availability', compact('vehicles', 'startDate', 'endDate', 'types', 'dates'));
    }
}