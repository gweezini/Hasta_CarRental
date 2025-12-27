<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Vehicle;
use App\Http\Controllers\AdminVehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CustomerController;


Route::get('/', function () {
    $vehicles = Vehicle::all(); 
    return view('welcome', compact('vehicles'));
});

Route::get('/dashboard', function () {
    $vehicles = \App\Models\Vehicle::all();
    return view('dashboard', compact('vehicles')); 
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard'); 
})->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/vehicle', [CarController::class, 'index'])->name('admin.vehicle.index');
    Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('/vehicle/{id}/show', [CarController::class, 'show'])->name('admin.vehicle.show');
    Route::get('/vehicle/{id}/edit', [CarController::class, 'edit'])->name('admin.vehicle.edit');
    Route::put('/vehicle/{id}', [CarController::class, 'update'])->name('admin.vehicle.update');
});

// The {id} represents the vehicle ID
Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
require __DIR__.'/auth.php';
