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
                    $stamps = floor($hours / 3);

                    // Voucher Logic...
                    if ($request->filled('selected_voucher_id')) {
                        $voucher = UserVoucher::find($request->selected_voucher_id);
                        if ($voucher && $voucher->user_id == Auth::id() && $voucher->is_active) {
                            if ($voucher->type === 'percent') $discount = ($subtotal * $voucher->value) / 100;
                            elseif ($voucher->type === 'fixed') $discount = $voucher->value;
                            elseif ($voucher->type === 'free_hours') {
                                $applicable = min($hours, (int)$voucher->value);
                                $discount = $applicable * $vehicle->price_per_hour;
                            }
                            $voucherMessage = "Voucher Applied: " . $voucher->name;
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
            $userVoucher = UserVoucher::find($request->selected_voucher_id);
            if ($userVoucher && $userVoucher->user_id == Auth::id() && $userVoucher->is_active) {
                if ($userVoucher->type === 'percent') $discount = ($subtotal * $userVoucher->value) / 100;
                elseif ($userVoucher->type === 'fixed') $discount = $userVoucher->value;
                elseif ($userVoucher->type === 'free_hours') {
                    $applicable = min($hours, (int)$userVoucher->value);
                    $discount = $applicable * $vehicle->price_per_hour;
                }
                $voucherId = $userVoucher->id;
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
            $stampsEarned = floor($hours / 3);
            if($stampsEarned > 0) {
                $card = Auth::user()->loyaltyCard ?? LoyaltyCard::create(['user_id' => Auth::id()]);
                $card->stamps += $stampsEarned;
                $card->save();
            }

            if ($userVoucher) {
                $userVoucher->is_active = false;
                $userVoucher->used_at = now();
                $userVoucher->save();
            }

            // 跳转
            if (Auth::user()->usertype === 'admin') {
                return redirect(route('admin.dashboard'))->with('success', 'Booking created successfully!');
            }

            return redirect(route('profile.edit'))->with('success', 'Booking created successfully! Please wait for verification.');
            
        } catch (\Exception $e) {
            Log::error('Booking creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }
}