<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\LoyaltyCard;
use App\Models\UserVoucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    // 1. SHOW METHOD (保持不变，计算价格)
    public function show($id, Request $request)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        $myVouchers = Auth::user()->userVouchers()
            ->whereNull('used_at') 
            ->with('voucher') 
            ->get();

        $prices = ['office' => 0, 'campus' => 2.50, 'taman_u' => 7.50, 'jb' => 25];

        $hours = 0;
        $subtotal = 0;
        $deliveryFee = 0; 
        $discount = 0;
        $total = 0;
        $stamps = 0;
        $voucherMessage = "";

        $pickupLoc = $request->query('pickup_location', 'office');
        $dropoffLoc = $request->query('dropoff_location', 'office');

        $pickupFee = $prices[$pickupLoc] ?? 0;
        $dropoffFee = $prices[$dropoffLoc] ?? 0;
        $deliveryFee = $pickupFee + $dropoffFee;

        // 智能时间处理
        $startString = null;
        $endString = null;

        if ($request->filled('start_date') && $request->filled('start_time')) {
            $startString = $request->start_date . ' ' . $request->start_time;
        } elseif ($request->filled('start_time')) {
            $startString = $request->start_time;
        }

        if ($request->filled('stop_date') && $request->filled('stop_time')) {
            $endString = $request->stop_date . ' ' . $request->stop_time;
        } elseif ($request->filled('end_time')) { 
             $endString = $request->end_time;
        }

        if ($startString && $endString) {
            try {
                $start = Carbon::parse($startString);
                $end = Carbon::parse($endString);

                if ($end->gt($start)) {
                    $hours = ceil($start->floatDiffInHours($end));
                    if ($hours < 1) $hours = 1;
                    $subtotal = $hours * $vehicle->price_per_hour;
                    $stamps = ($hours > 3) ? 1 : 0;

                    // Voucher Logic...
                    if ($request->filled('selected_voucher_id')) {
                        $userVoucher = UserVoucher::with('voucher')->find($request->selected_voucher_id);
                        if ($userVoucher && $userVoucher->voucher && $userVoucher->user_id == Auth::user()->matric_staff_id && $userVoucher->voucher->is_active) {
                            $v_master = $userVoucher->voucher;
                            if ($v_master->type === 'percent') $discount = ($subtotal * $v_master->value) / 100;
                            elseif ($v_master->type === 'fixed') $discount = $v_master->value;
                            elseif ($v_master->type === 'free_hours') {
                                $applicable = min($hours, (int)$v_master->value);
                                $discount = $applicable * $vehicle->price_per_hour;
                            }
                            $voucherMessage = "Voucher Applied: " . $v_master->name;
                        }
                    } elseif ($request->filled('manual_code')) {
                        if (strtoupper($request->manual_code) === 'WELCOME10') {
                            $discount = 10;
                            $voucherMessage = "Code Applied: WELCOME10";
                        }
                    }
                }
            } catch (\Exception $e) {
                // Ignore parsing errors on show
            }
        }

        $total = max(0, $subtotal + $deliveryFee - $discount);

        return view('booking', compact(
            'vehicle', 'myVouchers', 'hours', 'subtotal', 
            'discount', 'deliveryFee', 'total', 'stamps', 'voucherMessage',
            'pickupLoc', 'dropoffLoc'
        ));
    }

    // 2. STORE METHOD (这里是重点修复)
    public function store(Request $request)
    {
        Log::info('BookingController@store called', ['input' => $request->all()]);
        
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_time' => 'required', 
            'end_time'   => 'required', 
            'receipt_image' => 'nullable|image|max:2048',
            'name' => 'required|string', 
            'phone' => 'required|string',
            'pickup_location' => 'required|string',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        // 🔥 强制格式化时间，解决 00:00:00 问题
        try {
            $pickupDateTime = Carbon::parse($request->start_time)->format('Y-m-d H:i:s');
            $returnDateTime = Carbon::parse($request->end_time)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid date format.');
        }

        // 计算价格
        $start = Carbon::parse($pickupDateTime);
        $end = Carbon::parse($returnDateTime);
        
        if ($end->lte($start)) {
            return redirect()->back()->with('error', 'Return time must be after pickup time.');
        }

        // 24h Lead Time Check - Relaxed as per requirement to ignore if it becomes invalid during process
        // if (now()->diffInHours($start, false) < 24) {
        //    return redirect()->back()->with('error', 'Bookings must be made at least 24 hours in advance.');
        // }
        
        // Minimum 1 Hour Check
        if ($start->diffInMinutes($end, false) < 60) {
            return redirect()->back()->with('error', 'Minimum rental time is 1 hour.');
        }

        $hours = ceil($start->floatDiffInHours($end));
        if ($hours < 1) $hours = 1;
        $subtotal = $hours * $vehicle->price_per_hour;

        // 计算运费
        $prices = ['office' => 0, 'campus' => 2.50, 'taman_u' => 7.50, 'jb' => 25];
        $pickupFee = $prices[$request->pickup_location] ?? 0;
        
        $isSameLocation = $request->has('same_location_checkbox') || $request->input('same_location_checkbox') == 'on';
        $dropoffLocation = $isSameLocation ? $request->pickup_location : $request->dropoff_location;
        $dropoffLocation = $dropoffLocation ?: 'office';

        $dropoffFee = $prices[$dropoffLocation] ?? 0;
        $deliveryFee = $pickupFee + $dropoffFee;

        // Voucher
        $discount = 0;
        $voucherId = null;
        $userVoucher = null;

        if ($request->filled('selected_voucher_id')) {
            $userVoucher = UserVoucher::with('voucher')->find($request->selected_voucher_id);
            // Fix: Check against matric_staff_id
            if ($userVoucher && $userVoucher->voucher && $userVoucher->user_id == Auth::user()->matric_staff_id && $userVoucher->voucher->is_active) {
                $v_master = $userVoucher->voucher;
                if ($v_master->type === 'percent') $discount = ($subtotal * $v_master->value) / 100;
                elseif ($v_master->type === 'fixed') $discount = $v_master->value;
                elseif ($v_master->type === 'free_hours') {
                    $applicable = min($hours, (int)$v_master->value);
                    $discount = $applicable * $vehicle->price_per_hour;
                }
                $voucherId = $userVoucher->voucher_id;
            }
        } elseif ($request->filled('manual_code')) {
             if (strtoupper($request->manual_code) === 'WELCOME10') $discount = 10;
        }

        $final_total = max(0, $subtotal + $deliveryFee - $discount);

        // 图片上传
        $receiptPath = null;
        if ($request->hasFile('receipt_image')) {
            $receiptPath = $request->file('receipt_image')->store('payments', 'public');
        }

        try {
            $booking = new Booking();
            $booking->user_id = Auth::id();
            $booking->vehicle_id = $vehicle->id;
            
            // 🔥 对应你的数据库列名
            $booking->pickup_date_time = $pickupDateTime; 
            $booking->return_date_time = $returnDateTime; 
            
            $booking->pickup_location = $request->pickup_location;
            $booking->custom_pickup_address = $request->custom_pickup_address ?? null;
            $booking->dropoff_location = $dropoffLocation;
            $booking->custom_dropoff_address = $request->custom_dropoff_address ?? null;
            
            // 🔥 对应你的数据库列名 customer_name
            $booking->customer_name = $request->name;
            $booking->customer_phone = $request->phone;
            $booking->emergency_contact_name = $request->emergency_name;
            $booking->emergency_contact_phone = $request->emergency_contact;
            
            $booking->total_rental_fee = $final_total;
            $booking->deposit_amount = $request->input('deposit_amount', 0);
            $booking->promo_code = $request->input('manual_code') ?? null;
            $booking->voucher_id = $voucherId;
            $booking->payment_receipt = $receiptPath;
            $booking->status = 'Waiting for Verification'; 
            
            $booking->save();

            // 积分 & Voucher 标记
            // Fix: Award 1 stamp only if booking is > 3 hours
            $stampAwarded = false;
            if ($hours > 3) {
                $card = Auth::user()->loyaltyCard ?? LoyaltyCard::create(['user_id' => Auth::id()]);
                $card->stamps += 1;
                $card->save();
                $stampAwarded = true;
            }

            if ($userVoucher) {
                $userVoucher->used_at = now();
                $userVoucher->save();
            }

            // 跳转
            if (Auth::user()->role === 'admin') {
                return redirect(route('admin.dashboard'))->with('success', 'Booking created successfully!');
            }

            return redirect(route('profile.edit'))->with([
                'success' => 'Booking created successfully! Please wait for verification.',
                'stamp_awarded' => $stampAwarded
            ]);
            
        } catch (\Exception $e) {
            Log::error('Booking creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }
    // 3. EDIT METHOD
    public function edit($id)
    {
        $booking = Booking::with('vehicle')->findOrFail($id);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'Approved') {
            return redirect()->route('profile.edit')->with('error', 'Only approved bookings can be modified.');
        }

        // Check time constraint: Modification allowed anytime BEFORE pickup
        if (now()->gte($booking->pickup_date_time)) {
            return redirect()->route('profile.edit')->with('error', 'Past bookings cannot be modified.');
        }

        return view('bookings.edit', compact('booking'));
    }

    // 4. UPDATE METHOD
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'Approved') {
             return redirect()->route('profile.edit')->with('error', 'Only approved bookings can be modified.');
        }

        // Check time constraint based on ORIGINAL pickup time
        if (now()->gte($booking->pickup_date_time)) {
             return redirect()->route('profile.edit')->with('error', 'Past bookings cannot be modified.');
        }

        $request->validate([
            'start_time' => 'required', 
            'end_time'   => 'required', 
            'pickup_location' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|string',
        ]);

        try {
            // Format dates
            $pickupDateTime = Carbon::parse($request->start_time)->format('Y-m-d H:i:s');
            $returnDateTime = Carbon::parse($request->end_time)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid date format.');
        }

        // Price Calculation Logic (Recalculate based on new details)
        $vehicle = $booking->vehicle;
        
        $start = Carbon::parse($pickupDateTime);
        $end = Carbon::parse($returnDateTime);
        
        if ($end->lte($start)) {
            return redirect()->back()->with('error', 'Return time must be after pickup time.');
        }

        // Minimum 1 Hour Check
        if ($start->diffInMinutes($end, false) < 60) {
             return redirect()->back()->with('error', 'Minimum rental time is 1 hour.');
        }

        $hours = ceil($start->floatDiffInHours($end));
        if ($hours < 1) $hours = 1;
        $subtotal = $hours * $vehicle->price_per_hour;

        // Delivery Fee
        $prices = ['office' => 0, 'campus' => 2.50, 'taman_u' => 7.50, 'jb' => 25];
        $pickupFee = $prices[$request->pickup_location] ?? 0;
        
        $isSameLocation = $request->has('same_location_checkbox') || $request->input('same_location_checkbox') == 'on';
        $dropoffLocation = $isSameLocation ? $request->pickup_location : $request->dropoff_location;
        $dropoffLocation = $dropoffLocation ?: 'office';

        $dropoffFee = $prices[$dropoffLocation] ?? 0;
        $deliveryFee = $pickupFee + $dropoffFee;

        // Apply existing discount if any (Assuming percentage logic, if it was fixed amount it might be tricky)
        // For simplicity, we'll re-apply the voucher logic if voucher_id exists
        $discount = 0;
        if ($booking->voucher_id) {
            $userVoucher = \App\Models\UserVoucher::with('voucher')->find($booking->voucher_id); 
            if ($userVoucher && $userVoucher->voucher) {
                 $v_master = $userVoucher->voucher;
                 if ($v_master->type === 'percent') $discount = ($subtotal * $v_master->value) / 100;
                 elseif ($v_master->type === 'fixed') $discount = $v_master->value;
                 elseif ($v_master->type === 'free_hours') {
                    $applicable = min($hours, (int)$v_master->value);
                    $discount = $applicable * $vehicle->price_per_hour;
                 }
            }
        } elseif ($booking->promo_code === 'WELCOME10') {
            $discount = 10;
        }

        $final_total = max(0, $subtotal + $deliveryFee - $discount);

        // Update Booking
        $booking->pickup_date_time = $pickupDateTime;
        $booking->return_date_time = $returnDateTime;
        $booking->pickup_location = $request->pickup_location;
        $booking->custom_pickup_address = $request->custom_pickup_address ?? null;
        $booking->dropoff_location = $dropoffLocation;
        $booking->custom_dropoff_address = $request->custom_dropoff_address ?? null;
        
        $booking->customer_name = $request->name;
        $booking->customer_phone = $request->phone;
        $booking->emergency_contact_name = $request->emergency_name;
        $booking->emergency_contact_phone = $request->emergency_contact;
        
        $booking->total_rental_fee = $final_total;
        
        // Reset status for re-approval
        $booking->status = 'Waiting for Verification';
        $booking->payment_verified = false;
        
        $booking->save();

        return redirect()->route('profile.edit')->with('success', 'Booking updated successfully! It has been submitted for re-approval.');
    }

    // 5. DELETE METHOD (Cancellation)
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Check 24h constraint
        // now() + 24h should be BEFORE pickup time. 
        // Equiv: diffInHours between now and pickup must be > 24.
        $hoursUntilPickup = now()->diffInHours($booking->pickup_date_time, false);
        
        if ($hoursUntilPickup < 24) {
             return redirect()->back()->with('error', 'Bookings can only be cancelled 24 hours in advance.');
        }

        // Update status to Cancelled instead of deleting record
        $booking->status = 'Cancelled';
        $booking->save();

        return redirect()->route('profile.edit')->with('success', 'Booking cancelled successfully. If you have made a payment, please contact admin for refund.');
    }

    // 6. AJAX Price Calculation
    public function calculatePrice(Request $request)
    {
        try {
            $vehicle = Vehicle::findOrFail($request->vehicle_id);
            $start = Carbon::parse($request->start_time);
            $end = Carbon::parse($request->end_time);
            
            if ($end->lte($start)) {
                return response()->json(['error' => 'End time must be after start time'], 400);
            }

            $hours = ceil($start->floatDiffInHours($end));
            if ($hours < 1) $hours = 1;
            
            $subtotal = $hours * $vehicle->price_per_hour;

            // Delivery Fee
            $prices = ['office' => 0, 'campus' => 2.50, 'taman_u' => 7.50, 'jb' => 25];
            $pickupFee = $prices[$request->pickup_location] ?? 0;
            $dropoffFee = $prices[$request->dropoff_location] ?? 0;
            $deliveryFee = $pickupFee + $dropoffFee;

            // Voucher Logic
            $discount = 0;
            $voucherId = $request->selected_voucher_id;
            
            if ($voucherId) {
                $userVoucher = \App\Models\UserVoucher::with('voucher')->find($voucherId);
                
                // Fix: Check against matric_staff_id since that's how it's stored in user_vouchers
                if ($userVoucher && $userVoucher->voucher && $userVoucher->voucher->is_active && $userVoucher->user_id == Auth::user()->matric_staff_id) {
                     $v_master = $userVoucher->voucher;
                     if ($v_master->type === 'percent') $discount = ($subtotal * $v_master->value) / 100;
                     elseif ($v_master->type === 'fixed') $discount = $v_master->value;
                     elseif ($v_master->type === 'free_hours') {
                        $applicable = min($hours, (int)$v_master->value);
                        $discount = $applicable * $vehicle->price_per_hour;
                     }
                }
            } elseif ($request->filled('manual_code') && strtoupper($request->manual_code) === 'WELCOME10') {
                 $discount = 10;
            }

            $total = max(0, $subtotal + $deliveryFee - $discount);
            $stamps = ($hours > 3) ? 1 : 0;

            return response()->json([
                'hours' => $hours,
                'subtotal' => number_format($subtotal, 2),
                'delivery_fee' => number_format($deliveryFee, 2),
                'discount' => number_format($discount, 2),
                'total' => number_format($total, 2),
                'stamps' => $stamps
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}