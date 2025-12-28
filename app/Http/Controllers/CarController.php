<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index()
    {
        $vehicle = Vehicle::all();
        return view('admin.vehicle.index', compact('vehicle'));
    }

    public function show($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('admin.vehicle.show', compact('vehicle'));
    } 

    public function create()
    {
        $types = \Illuminate\Support\Facades\DB::table('vehicle_types')->get();
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
            'vehicle_image'     => 'required|nullable|image|max:2048',
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
        $types = \Illuminate\Support\Facades\DB::table('vehicle_types')->get();
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
            'price_per_hour' => 'required|numeric',
            'status' => 'required|string',
            'vehicle_image' => 'required|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('vehicle_image')) {
            if ($vehicle->vehicle_image) {
                Storage::disk('public')->delete($vehicle->vehicle_image);
            }
            
            $imagePath = $request->file('vehicle_image')->store('vehicles', 'public');
            $data['vehicle_image'] = $imagePath;
        }

        $vehicle->update($data);
        return redirect()->back()->with('success', 'Vehicle updated successfully!');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        if ($vehicle->vehicle_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($vehicle->vehicle_image);
        }

        $vehicle->delete();
        return redirect()->route('admin.vehicle.index')->with('success', 'Vehicle deleted successfully!');
    }
}