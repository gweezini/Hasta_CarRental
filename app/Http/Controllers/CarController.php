<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

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

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id); // Find car by ID or show error
        return view('admin.vehicle.edit', compact('vehicle'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $vehicle->update([
            'brand' => $request->brand,
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'price_per_hour' => $request->price_per_hour,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.vehicle.index')->with('success', 'Vehicle updated successfully!');
    }
}