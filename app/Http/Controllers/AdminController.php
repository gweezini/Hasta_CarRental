<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Faculty;
use App\Models\College;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\BookingStatusUpdated;

class AdminController extends Controller
{
    public function index()
    {
        $totalRevenue = Payment::where('status', 'Verified')->sum('amount'); 
        $todayRevenue = Payment::where('status', 'Verified')
                                       ->whereDate('created_at', Carbon::today())
                                       ->sum('amount');
        $pendingCount = Booking::where('status', 'Waiting for Verification')->count();
        $totalCars = Vehicle::count();
        $totalCustomers = User::where('role', 'customer')->count();

        $roadTaxAlerts = Vehicle::where('road_tax_expiry', '<=', Carbon::now()->addDays(30))
                                        ->orderBy('road_tax_expiry', 'asc')
                                        ->get();

        $insuranceAlerts = Vehicle::where('insurance_expiry', '<=', Carbon::now()->addDays(30))
                                          ->orderBy('insurance_expiry', 'asc')
                                          ->get();

        $facultyStats = User::where('role', 'customer')
                            ->select('faculty_id', DB::raw('count(*) as total'))
                            ->groupBy('faculty_id')
                            ->with('faculty')
                            ->get();
        
        $facultyLabels = $facultyStats->map(fn($item) => $item->faculty->name ?? 'Unknown');
        $facultyCounts = $facultyStats->pluck('total');

        $collegeStats = User::where('role', 'customer')
                           ->select('college_id', DB::raw('count(*) as total'))
                           ->groupBy('college_id')
                           ->with('college')
                           ->get();
                           
        $collegeLabels = $collegeStats->map(fn($item) => $item->college->name ?? 'Unknown');
        $collegeCounts = $collegeStats->pluck('total');

        $bookings = Booking::with(['user', 'vehicle'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'todayRevenue', 'pendingCount', 
            'totalCars', 'totalCustomers', 'bookings',
            'roadTaxAlerts', 'insuranceAlerts',
            'facultyLabels', 'facultyCounts', 'collegeLabels', 'collegeCounts'
        ));
    }

    public function reports(Request $request)
    {
        if (!Auth::user()->isTopManagement()) {
            return redirect()->route('admin.dashboard')->with('error', 'Authorized personnel only.');
        }

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
                             ->groupBy('date_key')
                             ->orderBy('date_key', 'asc')
                             ->get();

        $formattedRevenue = $revenueData->mapWithKeys(function ($item) use ($filter) {
            if ($filter == 'daily') {
                return [Carbon::parse($item->date_key)->format('d M') => $item->total];
            } elseif ($filter == 'monthly') {
                return [Carbon::create()->month($item->date_key)->format('F') => $item->total];
            } elseif ($filter == 'weekly') {
                return ['Week ' . substr($item->date_key, -2) => $item->total];
            }
            return [$item->date_key => $item->total];
        });

        $totalTransactions = $summaryQuery->count();
        $totalRevenueAmount = $summaryQuery->sum('amount');
        $avgOrderValue = $totalTransactions > 0 ? $totalRevenueAmount / $totalTransactions : 0;
        $highestTransaction = $summaryQuery->max('amount') ?? 0;

        return view('admin.reports', compact(
            'formattedRevenue', 
            'filter', 
            'totalTransactions', 
            'avgOrderValue', 
            'highestTransaction'
        ));
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
            'payment_verified' => true
        ]);
        
        if($booking->payment) {
            $booking->payment->update(['status' => 'Verified']);
        }

        if($booking->user) {
            $booking->user->notify(new BookingStatusUpdated($booking, 'Approved'));
        }

        return redirect()->route('admin.dashboard')->with('success', 'Booking approved & User notified!');
    }

    public function rejectPayment($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'Rejected']);
        
        if($booking->payment) {
            $booking->payment->update(['status' => 'Rejected']);
        }

        if($booking->user) {
            $booking->user->notify(new BookingStatusUpdated($booking, 'Rejected'));
        }

        return redirect()->route('admin.dashboard')->with('error', 'Booking rejected & User notified.');
    }

    public function allBookings()
    {
        $bookings = Booking::with(['user', 'vehicle'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function markAsReturned($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            $booking->status = 'Completed';
            $booking->save();

            return redirect()->back()->with('success', 'Vehicle marked as returned successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function notifications()
    {
        $now = \Carbon\Carbon::now();
        $threshold = $now->copy()->addDays(30);

        $activeList = \App\Models\Vehicle::where('road_tax_expiry', '<=', $threshold)
            ->orWhere('insurance_expiry', '<=', $threshold)
            ->get()->flatMap(function ($car) use ($threshold) {
                $items = [];
                if ($car->road_tax_expiry <= $threshold) {
                    $items[] = (object)[
                        'status_type' => 'active',
                        'type' => 'Road Tax',
                        'message' => "Road Tax for {$car->brand} {$car->model} ({$car->plate_number})",
                        'date' => $car->road_tax_expiry,
                        'car_id' => $car->id,
                        'is_expired' => \Carbon\Carbon::parse($car->road_tax_expiry)->isPast()
                    ];
                }
                if ($car->insurance_expiry <= $threshold) {
                    $items[] = (object)[
                        'status_type' => 'active',
                        'type' => 'Insurance',
                        'message' => "Insurance for {$car->brand} {$car->model} ({$car->plate_number})",
                        'date' => $car->insurance_expiry,
                        'car_id' => $car->id,
                        'is_expired' => \Carbon\Carbon::parse($car->insurance_expiry)->isPast()
                    ];
                }
                return $items;
            })->sortBy('date');

        $resolvedList = \App\Models\Vehicle::where('road_tax_expiry', '>', $threshold)
            ->where('insurance_expiry', '>', $threshold)
            ->orderBy('updated_at', 'desc') 
            ->get()->map(function ($car) {
                return (object)[
                    'status_type' => 'resolved',
                    'type' => 'Updated',
                    'message' => "Documents verified for {$car->brand} {$car->model} ({$car->plate_number})",
                    'date' => $car->updated_at, 
                    'car_id' => $car->id,
                    'is_expired' => false
                ];
            });

        return view('admin.notifications', compact('activeList', 'resolvedList'));
    }
}