<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Vehicle;
use App\Http\Controllers\AdminVehicleController;

Route::get('/', function () {
    // 1. Fetch all vehicles from your database
    $vehicles = Vehicle::all(); 

    // 2. Pass the $vehicles variable to the 'welcome' view
    return view('welcome', compact('vehicles'));
});


Route::get('/dashboard', function () {
    $vehicles = \App\Models\Vehicle::all();
    return view('dashboard', compact('vehicles')); 
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
