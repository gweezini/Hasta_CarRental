<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Vehicle;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\UserVoucherController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    if (Auth::user()->isStaff()) {
        return redirect()->route('admin.dashboard');
    }

    $vehicles = \App\Models\Vehicle::all(); 
    return view('dashboard', compact('vehicles')); 

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//  Admin Routes (Staff Access)
Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Bookings
    Route::get('/bookings', [AdminController::class, 'allBookings'])->name('admin.bookings.index');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('admin.customers.show');
    
    // Vehicles (Fleet Management)
    Route::get('/vehicle', [CarController::class, 'index'])->name('admin.vehicle.index');
    Route::get('/vehicle/create', [CarController::class, 'create'])->name('admin.vehicle.create');
    Route::post('/vehicle', [CarController::class, 'store'])->name('admin.vehicle.store');
    Route::get('/vehicle/{id}/show', [CarController::class, 'show'])->name('admin.vehicle.show');
    Route::get('/vehicle/{id}/edit', [CarController::class, 'edit'])->name('admin.vehicle.edit');
    Route::put('/vehicle/{id}', [CarController::class, 'update'])->name('admin.vehicle.update');
    Route::delete('/vehicle/{id}', [CarController::class, 'destroy'])->name('admin.vehicle.destroy');

    // Payment Verification Actions
    Route::get('/booking/{id}/verify', [AdminController::class, 'verifyPayment'])->name('admin.payment.verify');
    Route::post('/booking/{id}/approve', [AdminController::class, 'approvePayment'])->name('admin.payment.approve');
    Route::post('/booking/{id}/reject', [AdminController::class, 'rejectPayment'])->name('admin.payment.reject');

    Route::post('/booking/{id}/return', [AdminController::class, 'markAsReturned'])->name('admin.booking.return');
    
    // 🔥🔥🔥 Notification Center 
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('admin.notifications');

    // Voucher Management (Staff)
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('admin.vouchers.index');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('admin.vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('admin.vouchers.store');
    Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('admin.vouchers.edit');
    Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('admin.vouchers.update');
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('admin.vouchers.destroy');
});

// 🔥 User Booking Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/confirm-booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{id}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/booking/{id}', [BookingController::class, 'update'])->name('booking.update');
    Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
    Route::post('/booking/calculate', [BookingController::class, 'calculatePrice'])->name('booking.calculate');
    Route::get('/my-vouchers', [UserVoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/voucher/redeem-code', [UserVoucherController::class, 'redeemCode'])->name('vouchers.redeem.code');
    Route::post('/voucher/redeem-loyalty', [UserVoucherController::class, 'redeemLoyalty'])->name('vouchers.redeem.loyalty');
});

require __DIR__.'/auth.php';