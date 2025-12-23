<?php

use Illuminate\Support\Facades\Route;
use App\Models\Vehicle;
use App\Http\Controllers\AdminVehicleController;

Route::get('/', function () {
    $vehicles = Vehicle::all(); 

    return view('welcome', ['vehicles' => $vehicles]);
});