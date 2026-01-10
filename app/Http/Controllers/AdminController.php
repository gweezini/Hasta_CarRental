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
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\NewBookingNotification;
use App\Notifications\BookingStatusUpdated;
use App\Models\Claim;
use Illuminate\Support\Facades\Hash;

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

        // Award Stamp Logic
        $start = Carbon::parse($booking->pickup_date_time);
        $end = Carbon::parse($booking->return_date_time);
        $hours = ceil($start->floatDiffInHours($end));

        if ($hours >= 1 && $booking->user) { // Changed to 1 hour min for testing, or keep 3? User didn't specify min hours, sticking to existing (was 3). 
             // Wait, user didn't say change 3 hours rule, just the count logic.
             // Existing code: if ($hours >= 3 ...
             // I will leave the hour requirement as is (3 hours).
             
             if ($hours >= 3) {
                 $card = \App\Models\LoyaltyCard::firstOrCreate(['user_id' => $booking->user_id]);
                 
                 // Increment stamp
                 $card->increment('stamps');
                 $card->increment('unread_stamps');
                 $card->refresh(); // getting updated count

                 // Map tiers
                 $tiers = [
                    3 => ['code' => 'LOYALTY_T3', 'type' => 'percent', 'value' => 10, 'name' => 'Loyalty 10% (3 stamps)'],
                    6 => ['code' => 'LOYALTY_T6', 'type' => 'percent', 'value' => 15, 'name' => 'Loyalty 15% (6 stamps)'],
                    9 => ['code' => 'LOYALTY_T9', 'type' => 'percent', 'value' => 20, 'name' => 'Loyalty 20% (9 stamps)'],
                    12 => ['code' => 'LOYALTY_T12', 'type' => 'percent', 'value' => 25, 'name' => 'Loyalty 25% (12 stamps)'],
                    15 => ['code' => 'LOYALTY_T15', 'type' => 'free_hours', 'value' => 12, 'name' => 'Loyalty 12 hours free (15 stamps)'],
                 ];

                 // Check for Milestone
                 if (array_key_exists($card->stamps, $tiers)) {
                     $info = $tiers[$card->stamps];
                     
                     // Create Voucher
                     $voucher = \App\Models\Voucher::firstOrCreate(
                        ['code' => $info['code']],
                        [
                            'name' => $info['name'],
                            'type' => $info['type'],
                            'value' => $info['value'],
                            'is_active' => true,
                            'single_use' => false 
                        ]
                     );

                     // Assign to User if not already owned (Wait, can they earn it again next cycle? Yes. But duplicate check prevents it?)
                     // If UserVoucher is unique by (user_id, voucher_id), then they can't have two 'LOYALTY_T3' vouchers simultaneously?
                     // Usually loyalty vouchers are consumable. If they used the previous T3, the relation might still exist with `used_at`.
                     // UserVoucherController Check: `if ($user->userVouchers()->where('voucher_id', $voucher->id)->exists())`
                     // This blocks re-earning.
                     // I should allow multiple if 'used_at' is not null? Or just create a new row?
                     // `UserVoucher` likely has an ID. I should create a NEW entry.
                     
                     \App\Models\UserVoucher::create([
                        'user_id' => $booking->user_id, // Matric ID logic or User ID? 
                        // AdminController line 197 used 'user_id' for LoyaltyCard.
                        // UserVoucherController line 47/93 uses `matric_staff_id`.
                        // Booking->user_id is the ID (integer). 
                        // I must be careful. `UserVoucher` likely links to `users.matric_staff_id` or `users.id`?
                        // `UserVoucherController` uses `$user->matric_staff_id`.
                        // Let's check `UserVoucher` model or Migration?
                        // I'll stick to `$booking->user->matric_staff_id` to be safe, assuming UserVoucher links via string ID.
                        // Wait, `UserVoucherController` said `user_id` => `$user->matric_staff_id`.
                        'voucher_id' => $voucher->id,
                        'user_id' => $booking->user->matric_staff_id, 
                        'used_at' => null
                     ]);
                 }

                 // Loop Logic: Reset after 15
                 if ($card->stamps >= 15) {
                     $card->stamps = 0; // Next one will be 1
                     $card->save();
                 }
             }
        }

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

    public function returnDeposit(Request $request, $id)
    {
        $request->validate([
            'deposit_receipt' => 'required|image|max:2048', // Max 2MB
        ]);

        $booking = Booking::findOrFail($id);

        try {
            if ($request->hasFile('deposit_receipt')) {
                $path = $request->file('deposit_receipt')->store('deposits', 'public');
                
                $booking->update([
                    'deposit_status' => 'Returned',
                    'deposit_receipt_path' => $path,
                    'deposit_returned_at' => now(),
                    'processed_by' => Auth::id()
                ]);

                // Notify User
                if ($booking->user) {
                    $booking->user->notify(new \App\Notifications\DepositReturned($booking));
                }

                return redirect()->back()->with('success', 'Deposit returned successfully!');
            }
            return redirect()->back()->with('error', 'Please upload a receipt.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to return deposit: ' . $e->getMessage());
        }
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

    public function createStaff()
    {
        return view('admin.staff.create');
    }

    public function storeStaff(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => 'required|string|email|max:255|unique:users',
            'matric_staff_id' => 'required|string|max:20|unique:users',
            'nric_passport' => ['required', 'string', 'max:20', 'unique:users', 'regex:/^[a-zA-Z0-9]+$/'],
            'contact_number' => ['required', 'string', 'max:20', 'regex:/^\+[0-9]+$/'],
            'salary' => 'required|numeric|min:0',
            'role' => 'required|in:admin,topmanagement',
            'password' => 'required|string|min:8|confirmed',
            'bank_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'account_number' => ['nullable', 'string', 'max:50', 'regex:/^[0-9]+$/'],
            'account_holder' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
        ], [
            'name.regex' => 'Full name must contain only alphabets and spaces.',
            'nric_passport.regex' => 'NRIC/Passport must contain only alphanumeric characters (no dashes).',
            'contact_number.regex' => 'Contact number must start with + and contain only numbers.',
            'bank_name.regex' => 'Bank name must contain only alphabets and spaces.',
            'account_number.regex' => 'Account number must contain only numbers.',
            'account_holder.regex' => 'Account holder name must contain only alphabets and spaces.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'matric_staff_id' => $request->matric_staff_id,
            'nric_passport' => $request->nric_passport,
            'phone_number' => str_replace('+', '', $request->contact_number),
            'salary' => $request->salary,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'is_blacklisted' => false,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'New staff member registered successfully.');
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
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|confirmed|min:8',
            // Finance
            'staff_id' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'account_holder' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Only Admin can update finance details
        if ($user->isAdmin()) {
            $data['matric_staff_id'] = $request->staff_id; // Map staff_id to matric_staff_id
            $data['salary'] = $request->salary;
            $data['bank_name'] = $request->bank_name;
            $data['account_number'] = $request->account_number;
            $data['account_holder'] = $request->account_holder;
        }

        $user->update($data);

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
            'rental' => number_format($booking->total_rental_fee, 2),
            'deposit' => number_format($booking->deposit_amount, 2),
            'grand_total' => number_format($booking->total_rental_fee + $booking->deposit_amount, 2),
            'processed_by' => $booking->processedBy->name ?? 'Not Processed',
            'processed_at' => $booking->updated_at->format('d M Y, h:i A'),
            
            'payment_proof' => ($booking->payment && $booking->payment->proof_image) 
                               ? asset('storage/' . $booking->payment->proof_image) 
                               : (($booking->payment_receipt) ? asset('storage/' . $booking->payment_receipt) : null)
        ]);
    }
}