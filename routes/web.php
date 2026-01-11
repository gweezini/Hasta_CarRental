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
use App\Http\Controllers\FineController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::get('/blacklisted', function () {
    if (!Auth::check() || !Auth::user()->is_blacklisted) {
        return redirect()->route('home');
    }
    return view('blacklist.notice');
})->name('blacklist.notice');

Route::get('/faq', [App\Http\Controllers\FaqController::class, 'index'])->name('faq');
Route::get('/about', [App\Http\Controllers\StaticPageController::class, 'about'])->name('about');
Route::get('/privacy', [App\Http\Controllers\StaticPageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [App\Http\Controllers\StaticPageController::class, 'terms'])->name('terms');
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    if (Auth::user()->isStaff()) {
        return redirect()->route('admin.dashboard');
    }

    $vehicles = \App\Models\Vehicle::with('pricingTier.rules')->whereIn('status', ['Available', 'Rented'])->get();

    // Default availability
    foreach ($vehicles as $vehicle) {
        $vehicle->is_available = true;
    }
    
    // Check for new stamps (Moved from ProfileController)
    $showStampPopup = false;
    if (Auth::user()->loyaltyCard && Auth::user()->loyaltyCard->unread_stamps > 0) {
        $showStampPopup = true;
        Auth::user()->loyaltyCard->update(['unread_stamps' => 0]); 
    }

    return view('dashboard', compact('vehicles', 'showStampPopup')); 
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/bookings', [AdminController::class, 'allBookings'])->name('admin.bookings.index');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('admin.customers.show');
    Route::post('/customers/{id}/blacklist', [AdminController::class, 'toggleBlacklist'])->name('admin.customers.blacklist');
    
    // Vehicles (Fleet Management)
    Route::get('/vehicle', [CarController::class, 'index'])->name('admin.vehicle.index');
    Route::get('/vehicle/availability', [CarController::class, 'availability'])->name('admin.vehicle.availability');

    Route::get('/vehicle/create', [CarController::class, 'create'])->name('admin.vehicle.create');
    Route::post('/vehicle', [CarController::class, 'store'])->name('admin.vehicle.store');
    Route::get('/vehicle/{id}/show', [CarController::class, 'show'])->name('admin.vehicle.show');
    Route::get('/vehicle/{id}/edit', [CarController::class, 'edit'])->name('admin.vehicle.edit');
    Route::put('/vehicle/{id}', [CarController::class, 'update'])->name('admin.vehicle.update');
    Route::delete('/vehicle/{id}', [CarController::class, 'destroy'])->name('admin.vehicle.destroy');
    Route::post('/vehicle/{id}/maintenance', [CarController::class, 'storeMaintenance'])->name('admin.vehicle.maintenance.store');
    Route::post('/vehicle/{id}/maintenance', [CarController::class, 'storeMaintenance'])->name('admin.vehicle.maintenance.store');

    // Payment & Booking Actions
    Route::get('/booking/{id}/verify', [AdminController::class, 'verifyPayment'])->name('admin.payment.verify');
    Route::post('/booking/{id}/approve', [AdminController::class, 'approvePayment'])->name('admin.payment.approve');
    Route::post('/booking/{id}/reject', [AdminController::class, 'rejectPayment'])->name('admin.payment.reject');
    Route::post('/booking/{id}/return', [AdminController::class, 'markAsReturned'])->name('admin.booking.return');

    Route::get('/bookings/{id}/show', [AdminController::class, 'show'])->name('admin.bookings.show_detail');
    Route::get('/bookings/{id}/edit', [AdminController::class, 'editBooking'])->name('admin.bookings.edit');
    Route::put('/bookings/{id}', [AdminController::class, 'updateBooking'])->name('admin.bookings.update');
    
    // Fines Management
    Route::post('/bookings/{id}/fine', [AdminController::class, 'storeFine'])->name('admin.bookings.fine.store');
    Route::post('/bookings/{id}/return-deposit', [AdminController::class, 'returnDeposit'])->name('admin.bookings.return_deposit');
    Route::post('/bookings/{id}/forfeit-deposit', [AdminController::class, 'forfeitDeposit'])->name('admin.bookings.forfeit_deposit');
    Route::post('/fines/{id}/verify', [FineController::class, 'verify'])->name('admin.fines.verify');
    Route::post('/fines/{id}/pay', [FineController::class, 'pay'])->name('admin.fines.pay');
    Route::delete('/fines/{id}', [AdminController::class, 'deleteFine'])->name('admin.fines.destroy');
    
    // Notification Center
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('admin.notifications');

    // Voucher Management
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('admin.vouchers.index');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('admin.vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('admin.vouchers.store');
    Route::get('/vouchers/{voucher}', [VoucherController::class, 'show'])->name('admin.vouchers.show');
    Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('admin.vouchers.edit');
    Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('admin.vouchers.update');
    Route::post('/vouchers/{voucher}/toggle-status', [VoucherController::class, 'toggleStatus'])->name('admin.vouchers.toggle');

    // Staff Management
    Route::get('/staff-list', [AdminController::class, 'staffList'])->name('admin.staff.index');
    Route::get('/staff/create', [AdminController::class, 'createStaff'])->name('admin.staff.create');
    Route::post('/staff', [AdminController::class, 'storeStaff'])->name('admin.staff.store');
    Route::get('/staff/{id}', [AdminController::class, 'showStaff'])->name('admin.staff.show');
    
    Route::get('/my-profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::put('/my-profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // Claim System
    Route::get('/claims/create', [App\Http\Controllers\ClaimController::class, 'create'])->name('admin.claims.create');
    Route::post('/claims', [App\Http\Controllers\ClaimController::class, 'store'])->name('admin.claims.store');
    Route::get('/claims', [App\Http\Controllers\ClaimController::class, 'index'])->name('admin.claims.index');
    Route::post('/claims/{id}/status', [App\Http\Controllers\ClaimController::class, 'updateStatus'])->name('admin.claims.status');

    // Feedback
    Route::get('/feedbacks', [App\Http\Controllers\FeedbackController::class, 'index'])->name('admin.feedbacks.index');

    // Pricing Management
    Route::resource('pricing', App\Http\Controllers\AdminPricingController::class, ['as' => 'admin']);

});

Route::middleware(['auth'])->group(function () {
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/confirm-booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{id}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/booking/{id}', [BookingController::class, 'update'])->name('booking.update');
    Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
    Route::post('/booking/calculate', [BookingController::class, 'calculatePrice'])->name('booking.calculate');
    Route::get('/booking/vehicle/{id}/availability', [BookingController::class, 'getAvailability'])->name('booking.availability');
    
    Route::get('/my-vouchers', [UserVoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/voucher/redeem-code', [UserVoucherController::class, 'redeemCode'])->name('vouchers.redeem.code');
    Route::post('/voucher/redeem-loyalty', [UserVoucherController::class, 'redeemLoyalty'])->name('vouchers.redeem.loyalty');
    
    Route::post('/fines/{id}/receipt', [FineController::class, 'uploadReceipt'])->name('fines.upload');

    Route::get('/booking/{booking}/inspect', [App\Http\Controllers\InspectionController::class, 'create'])->name('inspections.create');
    Route::post('/booking/{booking}/inspect', [App\Http\Controllers\InspectionController::class, 'store'])->name('inspections.store');
    Route::get('/inspections/{inspection}', [App\Http\Controllers\InspectionController::class, 'show'])->name('inspections.show');
});

// Public Pricing
Route::get('/pricing', [App\Http\Controllers\PublicPricingController::class, 'index'])->name('pricing.index');


require __DIR__.'/auth.php';