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
     * 1. ADMIN DASHBOARD
     */
    public function index()
    {
        $totalRevenue = Booking::where('payment_verified', true)->sum('total_rental_fee'); 
        $todayRevenue = Booking::where('payment_verified', true)
                                ->whereDate('updated_at', Carbon::today())
                                ->sum('total_rental_fee');

        $pendingCount = Booking::where('status', 'Waiting for Verification')->count();
        $totalCars = Vehicle::count();
        $totalCustomers = User::where('role', 'customer')->count();

        $roadTaxAlerts = Vehicle::where('road_tax_expiry', '<=', Carbon::now()->addDays(30))
                                ->orderBy('road_tax_expiry', 'asc')->get();
        $insuranceAlerts = Vehicle::where('insurance_expiry', '<=', Carbon::now()->addDays(30))
                                  ->orderBy('insurance_expiry', 'asc')->get();

        $facultyStats = User::where('role', 'customer')
                            ->select('faculty_id', DB::raw('count(*) as total'))
                            ->groupBy('faculty_id')->with('faculty')->get();
        
        $facultyLabels = $facultyStats->map(fn($item) => str_replace('Faculty of ', '', $item->faculty->name ?? 'Unknown'));
        $facultyCounts = $facultyStats->pluck('total');

        $collegeStats = User::where('role', 'customer')
                            ->select('college_id', DB::raw('count(*) as total'))
                            ->groupBy('college_id')->with('college')->get();
        $collegeLabels = $collegeStats->map(fn($item) => $item->college->name ?? 'Unknown');
        $collegeCounts = $collegeStats->pluck('total');

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

    
     // NOTIFICATION CENTER (With Filter & Image Support)
    public function notifications(Request $request)
    {
        $now = Carbon::now();
        $threshold = $now->copy()->addDays(30);

        // A. Active Alerts 
        $activeList = Vehicle::where('road_tax_expiry', '<=', $threshold)
            ->orWhere('insurance_expiry', '<=', $threshold)
            ->get()
            ->flatMap(function ($car) use ($threshold) {
                $alerts = [];
                // 1. Check Road Tax
                if ($car->road_tax_expiry <= $threshold) {
                    $alerts[] = (object)[
                        'car_id' => $car->id,
                        'plate_number' => $car->plate_number,
                        'vehicle_image' => $car->vehicle_image,
                        'type' => 'Road Tax',                 
                        'message' => "Road Tax: {$car->plate_number}",
                        'date' => $car->road_tax_expiry,
                        'expiry_date' => $car->road_tax_expiry,
                        'days_left' => Carbon::now()->diffInDays(Carbon::parse($car->road_tax_expiry), false),
                        'is_expired' => Carbon::parse($car->road_tax_expiry)->isPast()
                    ];
                }
                // 2. Check Insurance
                if ($car->insurance_expiry <= $threshold) {
                    $alerts[] = (object)[
                        'car_id' => $car->id,
                        'plate_number' => $car->plate_number,
                        'vehicle_image' => $car->vehicle_image,
                        'type' => 'Insurance',                
                        'message' => "Insurance: {$car->plate_number}",
                        'date' => $car->insurance_expiry,
                        'expiry_date' => $car->insurance_expiry,
                        'days_left' => Carbon::now()->diffInDays(Carbon::parse($car->insurance_expiry), false),
                        'is_expired' => Carbon::parse($car->insurance_expiry)->isPast()
                    ];
                }
                return $alerts;
            })->sortBy('date');

        // B. Processed Records (Tab 2: 操作记录 + 筛选)
        
        $filter = $request->input('filter', 'week');
        $query = Vehicle::orderBy('updated_at', 'desc');

        switch ($filter) {
            case 'today': $query->whereDate('updated_at', Carbon::today()); break;
            case 'week': $query->where('updated_at', '>=', Carbon::now()->subDays(7)); break;
            case 'month': $query->where('updated_at', '>=', Carbon::now()->subDays(30)); break;
            case 'all': break;
        }

        $recentUpdates = $query->get()
            ->map(function ($car) use ($threshold) {
                return (object)[
                    'car_id' => $car->id,
                    'plate_number' => $car->plate_number,
                    'vehicle_image' => $car->vehicle_image, 
                    'updated_at' => $car->updated_at,
                    'road_tax_date' => $car->road_tax_expiry,
                    'insurance_date' => $car->insurance_expiry,
                    'rt_safe' => $car->road_tax_expiry > $threshold,
                    'ins_safe' => $car->insurance_expiry > $threshold,
                    'message' => "Update: {$car->plate_number}", 
                    'date' => $car->updated_at,
                ];
            });

        return view('admin.notifications', compact('activeList', 'recentUpdates', 'filter'));
    }
    /**
     * 3. CUSTOMER MANAGEMENT
     */
    public function customers()
    {
        $customers = User::where('role', 'customer')
                        ->with(['college', 'faculty'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
                        
        return view('admin.customers.index', compact('customers'));
    }

    public function showCustomer($id)
    {
        $customer = User::with(['college', 'faculty', 'bookings'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function toggleBlacklist(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->is_blacklisted) {
            $user->update(['is_blacklisted' => false, 'blacklist_reason' => null]);
            return redirect()->back()->with('success', 'User whitelisted!');
        } else {
            $request->validate(['blacklist_reason' => 'required|string|max:255']);
            $user->update(['is_blacklisted' => true, 'blacklist_reason' => $request->blacklist_reason]);
            return redirect()->back()->with('success', 'User blacklisted.');
        }
    }

    /**
     * 4. BOOKING ACTIONS
     */
    public function allBookings()
    {
        $bookings = Booking::with(['user', 'vehicle'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function verifyPayment($id)
    {
        $booking = Booking::with(['user', 'vehicle', 'payment'])->findOrFail($id);
        $payment = $booking->payment; 
        return view('admin.verify_payment', compact('booking', 'payment'));
    }

    public function approvePayment($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'Approved', 'payment_verified' => true]);
        if($booking->payment) $booking->payment->update(['status' => 'Verified']);
        if($booking->user) $booking->user->notify(new BookingStatusUpdated($booking, 'Approved'));
        return redirect()->route('admin.dashboard')->with('success', 'Booking approved!');
    }

    public function rejectPayment($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'Rejected', 'payment_verified' => false]);
        if($booking->payment) $booking->payment->update(['status' => 'Rejected']);
        if($booking->user) $booking->user->notify(new BookingStatusUpdated($booking, 'Rejected'));
        return redirect()->route('admin.dashboard')->with('error', 'Booking rejected.');
    }

    public function markAsReturned($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->status = 'Completed';
            $booking->save();
            return redirect()->back()->with('success', 'Vehicle marked as returned!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Operation failed.');
        }
    }

    /**
     * 5. REPORTS
     */
    public function reports(Request $request)
    {
        if (!Auth::user()->isTopManagement()) return redirect()->route('admin.dashboard')->with('error', 'Authorized personnel only.');

        $filter = $request->input('filter', 'monthly');
        $query = Payment::where('status', 'Verified');
        $summaryQuery = Payment::where('status', 'Verified'); 
        $groupBy = '';

        switch ($filter) {
            case 'daily':
                $dateCondition = Carbon::now()->subDays(30);
                $query->where('created_at', '>=', $dateCondition);
                $summaryQuery->where('created_at', '>=', $dateCondition);
                $groupBy = "DATE(created_at)"; 
                break;
            case 'weekly':
                $dateCondition = Carbon::now()->subWeeks(12); 
                $query->where('created_at', '>=', $dateCondition);
                $summaryQuery->where('created_at', '>=', $dateCondition);
                $groupBy = "YEARWEEK(created_at)";
                break;
            case 'yearly':
                $groupBy = "YEAR(created_at)"; 
                break;
            case 'monthly':
            default:
                $query->whereYear('created_at', Carbon::now()->year);
                $summaryQuery->whereYear('created_at', Carbon::now()->year);
                $groupBy = "MONTH(created_at)"; 
                break;
        }

        $revenueData = $query->selectRaw("$groupBy as date_key, SUM(amount) as total")
                             ->groupBy('date_key')->orderBy('date_key', 'asc')->get();

        $formattedRevenue = $revenueData->mapWithKeys(function ($item) use ($filter) {
            if ($filter == 'daily') return [Carbon::parse($item->date_key)->format('d M') => $item->total];
            if ($filter == 'monthly') return [Carbon::create()->month($item->date_key)->format('F') => $item->total];
            if ($filter == 'weekly') return ['Week ' . substr($item->date_key, -2) => $item->total];
            return [$item->date_key => $item->total];
        });

        $totalTransactions = $summaryQuery->count();
        $totalRevenueAmount = $summaryQuery->sum('amount');
        $avgOrderValue = $totalTransactions > 0 ? $totalRevenueAmount / $totalTransactions : 0;
        $highestTransaction = $summaryQuery->max('amount') ?? 0;

        return view('admin.reports', compact('formattedRevenue', 'filter', 'totalTransactions', 'avgOrderValue', 'highestTransaction'));
    }
}