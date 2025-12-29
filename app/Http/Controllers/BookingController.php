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
    public function show($id, Request $request)
    {
        // A. Get the Vehicle
        $vehicle = Vehicle::findOrFail($id);

        // B. Get User's Active Vouchers for the Dropdown
        // (Ensure 'vouchers' relationship exists in User model)
        $myVouchers = Auth::user()->vouchers()->where('status', 'active')->get();

        // C. Initialize Variables
        $hours = 0;
        $subtotal = 0;
        $discount = 0;
        $total = 0;
        $stamps = 0;
        $voucherMessage = "";

        // D. Calculation Logic (Only runs if user clicked "Update Price")
        if ($request->has(['start_time', 'end_time']) && $request->start_time && $request->end_time) {
            
            $start = Carbon::parse($request->start_time);
            $end = Carbon::parse($request->end_time);

            if ($end->gt($start)) {
                // 1. Calculate Hours & Subtotal
                $hours = ceil($start->floatDiffInHours($end)); 
                if ($hours < 1) $hours = 1;

                $subtotal = $hours * $vehicle->price_per_hour;
                $stamps = floor($hours / 3);

                // 2. Voucher Logic
                if ($request->has('selected_voucher_id') && $request->selected_voucher_id) {
                    $voucher = Voucher::find($request->selected_voucher_id);
                    if ($voucher && $voucher->user_id == Auth::id()) {
                        $discount = $voucher->amount;
                        $voucherMessage = "Voucher Applied: " . $voucher->name;
                    }
                } elseif ($request->has('manual_code') && $request->manual_code) {
                    $code = strtoupper($request->manual_code);
                    if ($code === 'WELCOME10') {
                        $discount = 10;
                        $voucherMessage = "Promo Code Applied: WELCOME10";
                    } else {
                        $voucherMessage = "Invalid Promo Code";
                    }
                }

                // 3. Final Total
                $total = max(0, $subtotal - $discount);
            }
        }

        return view('booking', compact(
            'vehicle', 'myVouchers', 'hours', 'subtotal', 
            'discount', 'total', 'stamps', 'voucherMessage'
        ));
    }

    // =========================================================
    // 2. STORE METHOD: Saves the Booking to Database
    // =========================================================
    public function store(Request $request)
    {
        // A. Validation
        $request->validate([
            'start_time'    => 'required|date',
            'end_time'      => 'required|date|after:start_time',
            'license_image' => 'required|image|max:2048', // Added max size
            'receipt_image' => 'required|image|max:2048',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        // B. Recalculate Logic (SECURITY CRITICAL)
        // We must re-do the math here so hackers can't just send "total_price=0"
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        
        $hours = ceil($start->floatDiffInHours($end));
        if ($hours < 1) $hours = 1;
        
        $subtotal = $hours * $vehicle->price_per_hour;
        $discount = 0;

        // C. Re-Apply Voucher Logic (So the database saves the discounted price)
        if ($request->filled('selected_voucher_id')) {
            $voucher = Voucher::find($request->selected_voucher_id);
            if ($voucher && $voucher->user_id == Auth::id()) {
                $discount = $voucher->amount;
                // Optional: Mark voucher as used
                // $voucher->status = 'used'; 
                // $voucher->save(); 
            }
        } elseif ($request->filled('manual_code')) {
             if (strtoupper($request->manual_code) === 'WELCOME10') {
                 $discount = 10;
             }
        }

        $final_total = max(0, $subtotal - $discount);

        // D. Handle File Uploads
        $licensePath = $request->file('license_image')->store('licenses', 'public');
        $receiptPath = $request->file('receipt_image')->store('payments', 'public');

        // E. Create Booking
        $booking = new Booking();
        $booking->user_id = Auth::id();
        $booking->vehicle_id = $vehicle->id;
        $booking->start_time = $start;
        $booking->end_time = $end;
        $booking->total_price = $final_total; // Save the discounted total
        $booking->license_image = $licensePath;
        $booking->payment_receipt = $receiptPath; // Ensure your DB column matches this name
        $booking->status = 'pending';
        $booking->save();

        // F. Handle Stamps
        $stampsEarned = floor($hours / 3);
        if($stampsEarned > 0) {
            $card = Auth::user()->loyaltyCard ?? LoyaltyCard::create(['user_id' => Auth::id()]);
            $card->stamps += $stampsEarned;
            $card->save();
        }

        return redirect('/dashboard')->with('success', 'Booking submitted successfully!');
    }
}