<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Vehicle;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            
            'pending' => Booking::where('status', 'Waiting for Verification')->count(),
            
            'active' => Booking::where('status', 'Approved')->count(),
            
            'revenue' => Payment::where('status', 'Verified')->sum('amount'),
        ];

        $bookings = Booking::with(['user', 'vehicle'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.dashboard', compact('bookings', 'stats'));
    }

    public function verifyPayment($id)
    {
        $booking = Booking::with(['user', 'vehicle', 'payment'])->findOrFail($id);
        $payment = $booking->payment; 
        
        // 这里的视图文件在 resources/views/admin/verify_payment.blade.php
        return view('admin.verify_payment', compact('booking', 'payment'));
    }

    public function approvePayment($id)
    {
        $booking = Booking::findOrFail($id);
        
        $booking->update([
            'status' => 'Approved',
            'payment_verified' => true
        ]);
        
        if($booking->payment) {
            $booking->payment->update(['status' => 'Verified']);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Booking approved successfully!');
    }

    public function rejectPayment($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update(['status' => 'Rejected']);
        
        if($booking->payment) {
            $booking->payment->update(['status' => 'Rejected']);
        }

        return redirect()->route('admin.dashboard')->with('error', 'Booking has been rejected.');
    }
}