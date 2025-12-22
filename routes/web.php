<?php

use Illuminate\Support\Facades\Route;
use App\Models\Vehicle;

Route::get('/', function () {
    $vehicles = Vehicle::all(); 

    return view('welcome', ['vehicles' => $vehicles]);
});