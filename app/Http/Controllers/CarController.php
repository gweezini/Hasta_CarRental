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
}