<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Voucher; 
use App\Models\Booking;
use App\Models\LoyaltyCard;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // =========================================================
    // 1. SHOW METHOD: Displays the form & Calculates Estimates
    // =========================================================
    // In BookingController.php

public function show($id, Request $request)
{
    $vehicle = Vehicle::findOrFail($id);
    $myVouchers = Auth::user()->vouchers()->get();

    // 1. Define Delivery Prices (Per Trip)
    $prices = [
        'office'   => 0,
        'campus'   => 2.50,
        'taman_u'  => 7.50,
        'jb'       => 25,
    ];

    // 2. Initialize Variables
    $hours = 0;
    $subtotal = 0;
    $deliveryFee = 0; 
    $discount = 0;
    $total = 0;
    $stamps = 0;
    $voucherMessage = "";

    // 3. Get Selected Locations (Default to 'office')
    $pickupLoc = $request->query('pickup_location', 'office');
    $dropoffLoc = $request->query('dropoff_location', 'office');

    // 4. Calculate Delivery Fee Immediately
    $pickupFee = $prices[$pickupLoc] ?? 0;
    $dropoffFee = $prices[$dropoffLoc] ?? 0;
    $deliveryFee = $pickupFee + $dropoffFee;

    // 5. Calculate Rental Price
    if ($request->has(['start_time', 'end_time']) && $request->start_time && $request->end_time) {
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);

        if ($end->gt($start)) {
            $hours = ceil($start->floatDiffInHours($end));
            if ($hours < 1) $hours = 1;

            $subtotal = $hours * $vehicle->price_per_hour;
            $stamps = floor($hours / 3);

            // Voucher Logic
            if ($request->has('selected_voucher_id') && $request->selected_voucher_id) {
                $voucher = Voucher::find($request->selected_voucher_id);
                if ($voucher && $voucher->user_id == Auth::id()) {
                    $discount = $voucher->amount;
                    $voucherMessage = "Voucher Applied: " . $voucher->name;
                }
            } elseif ($request->has('manual_code') && $request->manual_code) {
                if (strtoupper($request->manual_code) === 'WELCOME10') {
                    $discount = 10;
                    $voucherMessage = "Promo Code Applied: WELCOME10";
                }
            }
        }
    }

    // 6. Final Total calculation
    $total = max(0, $subtotal + $deliveryFee - $discount);

    return view('booking', compact(
        'vehicle', 'myVouchers', 'hours', 'subtotal', 
        'discount', 'deliveryFee', 'total', 'stamps', 'voucherMessage',
        'pickupLoc', 'dropoffLoc'
    ));
}

public function store(Request $request)
{
    $request->validate([
        'start_time' => 'required|date',
        'end_time'   => 'required|date|after:start_time',
        'license_image' => 'required|image|max:2048',
        'receipt_image' => 'required|image|max:2048',
        'pickup_location' => 'required',
        'custom_pickup_address' => 'required_if:pickup_location,!=,office|nullable|string',
        'custom_dropoff_address' => 'required_if:dropoff_location,!=,office|nullable|string',
        'name' => 'required|string',
        'phone' => 'required|string',
        'emergency_name' => 'required|string',
        'emergency_contact' => 'required|string',
    ]);

    $vehicle = Vehicle::findOrFail($request->vehicle_id);

    // 1. Re-calculate Rental Price
    $start = Carbon::parse($request->start_time);
    $end = Carbon::parse($request->end_time);
    $hours = ceil($start->floatDiffInHours($end));
    if ($hours < 1) $hours = 1;
    $subtotal = $hours * $vehicle->price_per_hour;

    // 2. Re-calculate Delivery Fee (Security)
    $prices = ['office' => 0, 'campus' => 2.50, 'taman_u' => 7.50, 'jb' => 25];
    $pickupFee = $prices[$request->pickup_location] ?? 0;
    
    // Handle dropoff location
    $dropoffLocation = $request->filled('same_location_checkbox') ? $request->pickup_location : $request->dropoff_location;
    $dropoffFee = $prices[$dropoffLocation] ?? 0;
    $deliveryFee = $pickupFee + $dropoffFee;

    // 3. Calculate Discount & Voucher ID
    $discount = 0;
    $voucherId = null;
    if ($request->filled('selected_voucher_id')) {
        $voucher = Voucher::find($request->selected_voucher_id);
        if ($voucher && $voucher->user_id == Auth::id()) {
            $discount = $voucher->amount;
            $voucherId = $voucher->id;
        }
    } elseif ($request->filled('manual_code')) {
         if (strtoupper($request->manual_code) === 'WELCOME10') $discount = 10;
    }

    $final_total = max(0, $subtotal + $deliveryFee - $discount);

    // 4. File Uploads
    $licensePath = $request->file('license_image')->store('licenses', 'public');
    $receiptPath = $request->file('receipt_image')->store('payments', 'public');

    // 5. Save to Database
    $booking = new Booking();
    $booking->user_id = Auth::id();
    $booking->vehicle_id = $vehicle->id;
    
    // Dates & Times
    $booking->pickup_date_time = $start; 
    $booking->return_date_time = $end;
    
    // Locations
    $booking->pickup_location = $request->pickup_location;
    $booking->custom_pickup_address = $request->custom_pickup_address ?? null;
    $booking->dropoff_location = $dropoffLocation;
    $booking->custom_dropoff_address = $request->custom_dropoff_address ?? null;
    
    // Customer Info
    $booking->customer_name = $request->name;
    $booking->customer_phone = $request->phone;
    $booking->emergency_contact_name = $request->emergency_name;
    $booking->emergency_contact_phone = $request->emergency_contact;
    
    // Financials
    $booking->total_rental_fee = $final_total;
    $booking->voucher_id = $voucherId;
    
    // Files
    $booking->license_image = $licensePath;
    $booking->payment_receipt = $receiptPath;
    
    // Status
    $booking->status = 'pending';
    $booking->save();

    // 6. Stamps Logic
    $stampsEarned = floor($hours / 3);
    if($stampsEarned > 0) {
        $card = Auth::user()->loyaltyCard ?? LoyaltyCard::create(['user_id' => Auth::id()]);
        $card->stamps += $stampsEarned;
        $card->save();
    }

    return redirect('/dashboard')->with('success', 'Booking submitted successfully!');
}
}