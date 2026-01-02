<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\BookingStatusUpdated;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard with stats and charts.
     */
    public function index()
    {
        // 1. Total Revenue: Calculate from Bookings directly (more reliable)
        // Only count bookings where payment_verified is true
        $totalRevenue = Booking::where('payment_verified', true)->sum('total_rental_fee'); 
        
        // 2. Today's Revenue: Calculate based on 'updated_at' of verified bookings
        // This ensures actions taken TODAY (Approve/Return) are counted TODAY
        $todayRevenue = Booking::where('payment_verified', true)
                                ->whereDate('updated_at', Carbon::today())
                                ->sum('total_rental_fee');

        // Basic Counters
        $pendingCount = Booking::where('status', 'Waiting for Verification')->count();
        $totalCars = Vehicle::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Vehicle Expiry Alerts (30 Days Threshold)
        $roadTaxAlerts = Vehicle::where('road_tax_expiry', '<=', Carbon::now()->addDays(30))
                                ->orderBy('road_tax_expiry', 'asc')->get();
        $insuranceAlerts = Vehicle::where('insurance_expiry', '<=', Carbon::now()->addDays(30))
                                  ->orderBy('insurance_expiry', 'asc')->get();

        // Chart Data: Students by Faculty
        $facultyStats = User::where('role', 'customer')->select('faculty_id', DB::raw('count(*) as total'))
                            ->groupBy('faculty_id')->with('faculty')->get();
        $facultyLabels = $facultyStats->map(fn($item) => $item->faculty->name ?? 'Unknown');
        $facultyCounts = $facultyStats->pluck('total');

        // Chart Data: Students by College
        $collegeStats = User::where('role', 'customer')->select('college_id', DB::raw('count(*) as total'))
                           ->groupBy('college_id')->with('college')->get();
        $collegeLabels = $collegeStats->map(fn($item) => $item->college->name ?? 'Unknown');
        $collegeCounts = $collegeStats->pluck('total');

        // Recent Bookings: Show top 10 most recently updated
        $bookings = Booking::with(['user', 'vehicle'])
                            ->orderBy('updated_at', 'desc')
                            ->take(10) 
                            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'todayRevenue', 'pendingCount', 
            'totalCars', 'totalCustomers', 'bookings',
            'roadTaxAlerts', 'insuranceAlerts',
            'facultyLabels', 'facultyCounts', 'collegeLabels', 'collegeCounts'
        ));
    }

    /**
     * Approve Payment Action
     */
    public function approvePayment($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Update status and verify payment
        // This updates the 'updated_at' timestamp, triggering Today's Revenue increase
        $booking->update([
            'status' => 'Approved',
            'payment_verified' => true
        ]);
        
        // Sync Payment table if exists
        if($booking->payment) {
            $booking->payment->update(['status' => 'Verified']);
        }

        if($booking->user) {
            $booking->user->notify(new BookingStatusUpdated($booking, 'Approved'));
        }

        return redirect()->route('admin.dashboard')->with('success', 'Booking approved and revenue updated!');
    }

    /**
     * Mark Vehicle as Returned (Completed)
     */
    public function markAsReturned($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Mark as Completed
            // This touches 'updated_at', keeping it in Today's Revenue logic
            $booking->status = 'Completed';
            $booking->save();

            return redirect()->back()->with('success', 'Vehicle marked as returned successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Operation failed: ' . $e->getMessage());
        }
    }

    /**
     * Reject Booking Action
     */
    public function rejectPayment($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Set payment_verified to false (removes from revenue)
        $booking->update([
            'status' => 'Rejected',
            'payment_verified' => false
        ]);
        
        if($booking->payment) {
            $booking->payment->update(['status' => 'Rejected']);
        }

        if($booking->user) {
            $booking->user->notify(new BookingStatusUpdated($booking, 'Rejected'));
        }

        return redirect()->route('admin.dashboard')->with('error', 'Booking rejected.');
    }

    // --- Auxiliary Methods ---

    public function verifyPayment($id)
    {
        $booking = Booking::with(['user', 'vehicle', 'payment'])->findOrFail($id);
        $payment = $booking->payment; 
        return view('admin.verify_payment', compact('booking', 'payment'));
    }

    public function allBookings()
    {
        $bookings = Booking::with(['user', 'vehicle'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function notifications()
    {
        $now = Carbon::now();
        $threshold = $now->copy()->addDays(30);

        $activeList = Vehicle::where('road_tax_expiry', '<=', $threshold)
            ->orWhere('insurance_expiry', '<=', $threshold)
            ->get()->flatMap(function ($car) use ($threshold) {
                $items = [];
                if ($car->road_tax_expiry <= $threshold) {
                    $items[] = (object)[
                        'status_type' => 'active', 'type' => 'Road Tax', 
                        'message' => "Road Tax for {$car->brand} {$car->model} ({$car->plate_number})",
                        'date' => $car->road_tax_expiry, 'car_id' => $car->id, 
                        'is_expired' => Carbon::parse($car->road_tax_expiry)->isPast()
                    ];
                }
                if ($car->insurance_expiry <= $threshold) {
                    $items[] = (object)[
                        'status_type' => 'active', 'type' => 'Insurance', 
                        'message' => "Insurance for {$car->brand} {$car->model} ({$car->plate_number})",
                        'date' => $car->insurance_expiry, 'car_id' => $car->id, 
                        'is_expired' => Carbon::parse($car->insurance_expiry)->isPast()
                    ];
                }
                return $items;
            })->sortBy('date');

        $resolvedList = Vehicle::where('road_tax_expiry', '>', $threshold)
            ->where('insurance_expiry', '>', $threshold)
            ->orderBy('updated_at', 'desc')->take(10)->get()->map(function ($car) {
                return (object)['status_type' => 'resolved', 'type' => 'Updated', 'message' => "Verified: {$car->plate_number}", 'date' => $car->updated_at, 'car_id' => $car->id, 'is_expired' => false];
            });

        return view('admin.notifications', compact('activeList', 'resolvedList'));
    }

    public function reports(Request $request) {
        if (!Auth::user()->isTopManagement()) return redirect()->route('admin.dashboard')->with('error', 'Authorized personnel only.');
        // (Assuming Report logic is maintained as is)
        return view('admin.reports'); 
    }
}