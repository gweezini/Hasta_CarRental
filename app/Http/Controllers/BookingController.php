<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Voucher; 
use App\Models\Booking;
use App\Models\LoyaltyCard;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
    Log::info('BookingController@store called', ['input' => $request->all()]);
    $request->validate([
        'vehicle_id' => 'required|exists:vehicles,id',
        'start_time' => 'required|date',
        'end_time'   => 'required|date|after:start_time',
        'receipt_image' => 'nullable|image|max:2048',
        'license_image' => 'nullable|image|max:2048',
        'pickup_location' => 'required|string',
        'custom_pickup_address' => 'nullable|string',
        'custom_dropoff_address' => 'nullable|string',
        'name' => 'required|string',
        'phone' => 'required|string',
        'emergency_name' => 'required|string',
        'emergency_contact' => 'required|string',
    ], [
        'start_time.required' => 'Please select a start date and time.',
        'end_time.required' => 'Please select an end date and time.',
        'end_time.after' => 'Return time must be after pickup time.',
    ]);

    $vehicle = Vehicle::findOrFail($request->vehicle_id);

    // 1. Re-calculate Rental Price
    $start = Carbon::parse($request->start_time);
    $end = Carbon::parse($request->end_time);
    $hours = ceil($start->floatDiffInHours($end));
    if ($hours < 1) $hours = 1;
    $subtotal = $hours * $vehicle->price_per_hour;

    // 2. Re-calculate Delivery Fee (INSERT LOGIC HERE)
    $prices = ['office' => 0, 'campus' => 2.50, 'taman_u' => 7.50, 'jb' => 25];
    $pickupFee = $prices[$request->pickup_location] ?? 0;
    
    // --- THE INSERTED LOGIC START ---
    // Check if the checkbox was present in the request
    $isSameLocation = $request->has('same_location_checkbox') || $request->input('same_location_checkbox') == 'on';

    // If same location is checked, use pickup. Otherwise, use the dropoff_location field.
    $dropoffLocation = $isSameLocation ? $request->pickup_location : $request->dropoff_location;

    // Ensure we have a fallback if dropoff_location was somehow empty
    if (!$dropoffLocation) {
        $dropoffLocation = 'office';
    }

    $dropoffFee = $prices[$dropoffLocation] ?? 0;
    $deliveryFee = $pickupFee + $dropoffFee;
    // --- THE INSERTED LOGIC END ---

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

    // 4. File Uploads (guarded — receipt may be missing or upload could fail)
    $licensePath = null;
    $receiptPath = null;
    if ($request->hasFile('receipt_image')) {
        $receiptPath = $request->file('receipt_image')->store('payments', 'public');
    }

    // 5. Save to Database
    try {
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
        // populate required DB columns
        $booking->deposit_amount = $request->input('deposit_amount', 0);
        $booking->promo_code = $request->input('manual_code') ?? null;
        $booking->voucher_id = $voucherId;
        
        // Files
        $booking->payment_receipt = $receiptPath;
        
        // Status
        $booking->status = 'pending';
        $booking->save();

        Log::info('Booking created', ['booking_id' => $booking->id, 'user_id' => Auth::id(), 'total' => $final_total]);

        // 6. Stamps Logic
        $stampsEarned = floor($hours / 3);
        if($stampsEarned > 0) {
            $card = Auth::user()->loyaltyCard ?? LoyaltyCard::create(['user_id' => Auth::id()]);
            $card->stamps += $stampsEarned;
            $card->save();
        }

        return redirect(route('profile.edit'))->with('success', 'Booking ID #' . $booking->id . ' created successfully! Awaiting payment verification.');
    } catch (\Exception $e) {
        Log::error('Booking creation failed: ' . $e->getMessage(), ['exception' => $e]);
        return redirect()->back()->with('error', 'Booking failed: ' . $e->getMessage());
    }

}
}