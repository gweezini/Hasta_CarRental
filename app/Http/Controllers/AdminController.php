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
use App\Models\Claim;

class AdminController extends Controller
{
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

        $allCustomers = User::where('role', 'customer')->with(['faculty', 'college'])->get();

        // Faculty Stats (PHP Groupping)
        $facultyStats = $allCustomers->groupBy(fn($u) => $u->faculty->name ?? 'Unknown')
                                     ->map->count();
        
        $facultyLabels = $facultyStats->keys()->map(fn($name) => str_replace('Faculty of ', '', $name))->values();
        $facultyCounts = $facultyStats->values();

        // College Stats (PHP Groupping)
        $collegeStats = $allCustomers->groupBy(fn($u) => $u->college->name ?? 'Unknown')
                                     ->map->count();
        
        $collegeLabels = $collegeStats->keys()->values();
        $collegeCounts = $collegeStats->values();

        $bookings = Booking::with(['user', 'vehicle', 'processedBy'])
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

    public function notifications(Request $request)
    {
        $now = Carbon::now();
        $threshold = $now->copy()->addDays(30);

        $activeList = Vehicle::where('road_tax_expiry', '<=', $threshold)
            ->orWhere('insurance_expiry', '<=', $threshold)
            ->get()
            ->flatMap(function ($car) use ($threshold) {
                $alerts = [];
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

    public function allBookings()
    {
        $bookings = Booking::with(['user', 'vehicle', 'inspections'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'vehicle', 'payment', 'processedBy', 'inspections', 'feedback', 'fines'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
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
        $booking->update([
            'status' => 'Approved', 
            'payment_verified' => true,
            'processed_by' => Auth::id()
        ]);
        if($booking->payment) $booking->payment->update(['status' => 'Verified']);
        if($booking->user) $booking->user->notify(new BookingStatusUpdated($booking, 'Approved'));
        return redirect()->route('admin.dashboard')->with('success', 'Booking approved!');
    }

    public function rejectPayment(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $booking->update([
            'status' => 'Rejected', 
            'payment_verified' => false,
            'rejection_reason' => $request->rejection_reason,
            'processed_by' => Auth::id()
        ]);
        if($booking->payment) $booking->payment->update(['status' => 'Rejected']);
        if($booking->user) $booking->user->notify(new BookingStatusUpdated($booking, 'Rejected'));
        return redirect()->route('admin.dashboard')->with('error', 'Booking rejected.');
    }

    public function markAsReturned($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Check if return inspection exists
            $hasReturnInspection = $booking->inspections()->where('type', 'return')->exists();
            
            if (!$hasReturnInspection) {
                return redirect()->back()->with('error', 'Cannot mark as returned. Customer has not submitted the return inspection form yet.');
            }

            $booking->status = 'Completed';
            $booking->processed_by = Auth::id(); 
            $booking->save();
            
            return redirect()->back()->with('success', 'Vehicle marked as returned!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Operation failed.');
        }
    }

    public function storeFine(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        \App\Models\Fine::create([
            'booking_id' => $id,
            'reason' => $request->reason,
            'amount' => $request->amount,
            'status' => 'Unpaid',
        ]);

        return redirect()->back()->with('success', 'Fine issued successfully.');
    }

    public function payFine($id)
    {
        $fine = \App\Models\Fine::findOrFail($id);
        $fine->update([
            'status' => 'Paid',
            'paid_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Fine marked as paid.');
    }

    public function deleteFine($id)
    {
        \App\Models\Fine::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Fine deleted.');
    }

    public function reports(Request $request) 
    {
        if (!Auth::user()->isTopManagement()) {
            return redirect()->route('admin.dashboard')->with('error', 'Authorized personnel only.');
        }

        $filter = $request->input('filter', 'monthly');
        
        $totalRevenueAmount = Booking::where('payment_verified', true)->sum('total_rental_fee');

        $staffs = User::whereIn('role', ['admin', 'topmanagement'])->get();
        $totalSalaries = $staffs->sum('salary');

        // Calculate Claims
        $claimsQuery = Claim::where('status', 'Approved');

        // Apply same date filtering (simplified based on general scope for now, 
        // to match exact revenue scope would require replicating the switch logic or scoping later.
        // But for Total Revenue Amount (line 225) it is GLOBAL sum (ignoring filter!).
        // Wait, line 225 `Booking::where(...)->sum(...)` is NOT filtered by date?
        // Let's look at lines 236-257. Those filter `$query` and `$summaryQuery` but `$totalRevenueAmount` at line 225 implies GLOBAL?
        // Ah, `$totalRevenueAmount` (line 225) seems to be ALL time?
        // But `$netProfit` uses it.
        // If the report shows "Performance Overview" for "Monthly", but Net Profit is All Time?
        // Let's check the view usage.
        // View Section `Gross Revenue` uses `$totalRevenueAmount`.
        // If line 225 is global, then the cards show global stats unless `$filter` logic updates them?
        // Line 225 is BEFORE the switch. So it is global.
        // However, this seems like a bug or design choice in existing code (Cards show total, Chart shows filtered).
        // I will follow the existing pattern: Calculate GLOBAL claims for the Net Profit card (which seems to be global context).
        
        $totalClaims = Claim::where('status', 'Approved')->sum('amount');

        $netProfit = $totalRevenueAmount - $totalSalaries - $totalClaims;

        $query = Booking::where('payment_verified', true)->with('processedBy'); 
        $summaryQuery = Booking::where('payment_verified', true); 
        $groupBy = '';

        switch ($filter) {
            case 'daily':
                $dateCondition = Carbon::now()->subDays(30);
                $query->where('updated_at', '>=', $dateCondition);
                $summaryQuery->where('updated_at', '>=', $dateCondition);
                $groupBy = "DATE(updated_at)"; 
                break;
            case 'weekly':
                $dateCondition = Carbon::now()->subWeeks(12); 
                $query->where('updated_at', '>=', $dateCondition);
                $summaryQuery->where('updated_at', '>=', $dateCondition);
                $groupBy = "YEARWEEK(updated_at)";
                break;
            case 'yearly':
                $groupBy = "YEAR(updated_at)"; 
                break;
            case 'monthly':
            default:
                $query->whereYear('updated_at', Carbon::now()->year);
                $summaryQuery->whereYear('updated_at', Carbon::now()->year);
                $groupBy = "MONTH(updated_at)"; 
                break;
        }

        $revenueData = $query->selectRaw("$groupBy as date_key, SUM(total_rental_fee) as total")
                             ->groupBy('date_key')
                             ->orderBy('date_key', 'asc')
                             ->get();

        $formattedRevenue = $revenueData->mapWithKeys(function ($item) use ($filter) {
            if ($filter == 'daily') return [Carbon::parse($item->date_key)->format('d M') => $item->total];
            if ($filter == 'monthly') return [Carbon::create()->month($item->date_key)->format('F') => $item->total];
            if ($filter == 'weekly') return ['Week ' . substr($item->date_key, -2) => $item->total];
            return [$item->date_key => $item->total];
        });

        $revenueList = Booking::where('payment_verified', true)
                             ->with(['user', 'vehicle', 'processedBy'])
                             ->orderBy('updated_at', 'desc')
                             ->take(20) 
                             ->get();
        
        $totalTransactions = $summaryQuery->count();
        $avgOrderValue = $totalTransactions > 0 ? $totalRevenueAmount / $totalTransactions : 0;
        $highestTransaction = $summaryQuery->max('total_rental_fee') ?? 0;

        // Prepare Chart Data
        $chartLabels = $formattedRevenue->keys();
        $chartValues = $formattedRevenue->values();

        // Comparison Logic (Current vs Previous)
        $currentPeriodTotal = 0;
        $previousPeriodTotal = 0;
        $comparisonLabel = '';

        switch ($filter) {
            case 'daily':
                $currentPeriodTotal = Booking::where('payment_verified', true)
                    ->whereDate('updated_at', Carbon::today())
                    ->sum('total_rental_fee');
                $previousPeriodTotal = Booking::where('payment_verified', true)
                    ->whereDate('updated_at', Carbon::yesterday())
                    ->sum('total_rental_fee');
                $comparisonLabel = "vs Yesterday";
                break;
            case 'weekly':
                $currentPeriodTotal = Booking::where('payment_verified', true)
                    ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->sum('total_rental_fee');
                $previousPeriodTotal = Booking::where('payment_verified', true)
                    ->whereBetween('updated_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                    ->sum('total_rental_fee');
                $comparisonLabel = "vs Last Week";
                break;
            case 'monthly':
                $currentPeriodTotal = Booking::where('payment_verified', true)
                    ->whereMonth('updated_at', Carbon::now()->month)
                    ->whereYear('updated_at', Carbon::now()->year)
                    ->sum('total_rental_fee');
                $previousPeriodTotal = Booking::where('payment_verified', true)
                    ->whereMonth('updated_at', Carbon::now()->subMonth()->month)
                    ->whereYear('updated_at', Carbon::now()->subMonth()->year)
                    ->sum('total_rental_fee');
                $comparisonLabel = "vs Last Month";
                break;
            case 'yearly':
                $currentPeriodTotal = Booking::where('payment_verified', true)
                    ->whereYear('updated_at', Carbon::now()->year)
                    ->sum('total_rental_fee');
                $previousPeriodTotal = Booking::where('payment_verified', true)
                    ->whereYear('updated_at', Carbon::now()->subYear()->year)
                    ->sum('total_rental_fee');
                $comparisonLabel = "vs Last Year";
                break;
        }

        $percentageChange = 0;
        if ($previousPeriodTotal > 0) {
            $percentageChange = (($currentPeriodTotal - $previousPeriodTotal) / $previousPeriodTotal) * 100;
        } elseif ($currentPeriodTotal > 0) {
             $percentageChange = 100; // From 0 to something is 100% increase (technically infinite, but 100 for display)
        }

        $comparisonData = [
            'current' => $currentPeriodTotal,
            'previous' => $previousPeriodTotal,
            'percentage' => round($percentageChange, 1),
            'label' => $comparisonLabel,
            'is_positive' => $percentageChange >= 0
        ];

        return view('admin.reports', compact(
            'formattedRevenue', 
            'filter', 
            'totalTransactions', 
            'totalRevenueAmount', 
            'avgOrderValue', 
            'highestTransaction',
            'staffs',        
            'totalSalaries',
            'netProfit',
            'totalClaims', 
            'revenueList',
            'chartLabels',
            'chartValues',
            'comparisonData'
        ));
    }

    public function staffList()
    {
        $staffs = User::whereIn('role', ['admin', 'topmanagement'])
                      ->with(['claims' => function($q) {
                          $q->where('status', 'Approved')
                            ->whereMonth('claim_date_time', Carbon::now()->month)
                            ->whereYear('claim_date_time', Carbon::now()->year);
                      }])
                      ->orderBy('name', 'asc')
                      ->get();

        $totalSalaries = $staffs->sum('salary');
        $totalClaims = $staffs->flatMap->claims->sum('amount');
        $totalMonthlyPayroll = $totalSalaries + $totalClaims;

        return view('admin.staff.index', compact('staffs', 'totalMonthlyPayroll'));
    }

    public function showStaff($id)
    {
        $staff = User::whereIn('role', ['admin', 'topmanagement'])
                     ->with(['claims' => function($q) {
                         $q->where('status', 'Approved')->orderBy('claim_date_time', 'desc');
                     }])
                     ->findOrFail($id);

        $monthlyClaims = $staff->claims->groupBy(function($claim) {
            return Carbon::parse($claim->claim_date_time)->format('M Y');
        });

        // Generate months from Join Date until Now
        $joinDate = Carbon::parse($staff->created_at)->startOfMonth();
        $currentMonth = Carbon::now()->startOfMonth();
        $months = collect([]);

        while ($currentMonth->gte($joinDate)) {
            $months->push($currentMonth->format('M Y'));
            $currentMonth->subMonth();
        }
        
        $payrollMonths = $months->merge($monthlyClaims->keys())
                                ->unique()
                                ->sort(function($a, $b) {
                                    return Carbon::parse("1 $b")->timestamp <=> Carbon::parse("1 $a")->timestamp;
                                });

        return view('admin.staff.show', compact('staff', 'monthlyClaims', 'payrollMonths'));
    }

    public function profile()
    {
        $user = Auth::user();
        
        // Eager load for payroll history
        $user->load(['claims' => function($q) {
            $q->where('status', 'Approved')->orderBy('claim_date_time', 'desc');
        }]);

        $monthlyClaims = $user->claims->groupBy(function($claim) {
            return Carbon::parse($claim->claim_date_time)->format('M Y');
        });

        // Generate months from Join Date until Now
        $joinDate = Carbon::parse($user->created_at)->startOfMonth();
        $currentMonth = Carbon::now()->startOfMonth();
        $months = collect([]);

        while ($currentMonth->gte($joinDate)) {
            $months->push($currentMonth->format('M Y'));
            $currentMonth->subMonth();
        }
        
        $payrollMonths = $months->merge($monthlyClaims->keys())
                                ->unique()
                                ->sort(function($a, $b) {
                                    return Carbon::parse("1 $b")->timestamp <=> Carbon::parse("1 $a")->timestamp;
                                });

        $admin = $user;
        return view('admin.profile', compact('user', 'admin', 'monthlyClaims', 'payrollMonths'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function showBookingJson($id)
    {
        $booking = Booking::with(['user', 'vehicle', 'payment', 'processedBy'])->findOrFail($id);
        
        return response()->json([
            'id' => $booking->id,
            'status' => $booking->status,
            'customer' => $booking->user->name,
            'phone' => $booking->user->phone_number ?? $booking->user->phone,
            'vehicle' => $booking->vehicle->brand . ' ' . $booking->vehicle->model,
            'plate' => $booking->vehicle->plate_number,
            'total' => number_format($booking->total_rental_fee, 2),
            'processed_by' => $booking->processedBy->name ?? 'Not Processed',
            'processed_at' => $booking->updated_at->format('d M Y, h:i A'),
            
            'payment_proof' => ($booking->payment && $booking->payment->proof_image) 
                               ? asset('storage/' . $booking->payment->proof_image) 
                               : 'https://via.placeholder.com/400x600?text=No+Receipt+Found'
        ]);
    }
}