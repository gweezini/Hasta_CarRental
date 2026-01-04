<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Vehicle;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
class ClaimController extends Controller
{
    /**
     * Show form for staff to create a claim.
     */
    public function create()
    {
        $vehicles = Vehicle::all();
    
        $myClaims = Claim::where('matric_staff_id', Auth::user()->matric_staff_id)
                         ->orderBy('created_at', 'desc')
                         ->get();
                         
        return view('admin.claims.create', compact('vehicles', 'myClaims'));
    }

    /**
     * Store a new claim.
     */
    public function store(Request $request)
    {
        $request->validate([
            'claim_type' => 'required|string',
            'vehicle_plate' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'claim_date' => 'required|date',
            'claim_time' => 'required',
            'description' => 'nullable|string',
            'receipt' => 'nullable|image|max:2048', 
        ]);

        $claim = new Claim();
        $claim->user_id = Auth::id(); // Assign the authenticated user's ID
        $claim->matric_staff_id = Auth::user()->matric_staff_id; 
        $claim->claim_type = $request->claim_type;
        $claim->vehicle_plate = $request->vehicle_plate;
        $claim->amount = $request->amount;
        $claim->claim_date_time = Carbon::parse($request->claim_date . ' ' . $request->claim_time);
        $claim->description = $request->description;
        $claim->status = 'Pending';

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('claims', 'public');
            $claim->payment_receipt = $path;
        }

        $claim->save();

        // Notify Top Management
        $topManagement = User::whereIn('role', ['topmanagement', 'admin'])->get();
        // Notification::send($topManagement, new \App\Notifications\ClaimSubmitted($claim));

        return redirect()->route('admin.claims.create')->with('success', 'Claim submitted successfully!');
    }

    /**
     * List claims for Top Management review.
     */
    public function index()
    {
        if (!Auth::user()->isTopManagement()) {
            return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access.');
        }
        $claims = Claim::with(['user'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.claims.index', compact('claims'));
    }

    /**
     * Approve or reject a claim.
     */
    public function updateStatus(Request $request, $id)
    {
        if (!Auth::user()->isTopManagement()) {
            return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'reason' => 'nullable|string',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $claim = Claim::findOrFail($id);

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('claim_proofs', 'public');
            $claim->receipt_path = $path;
        }

        $claim->update([
            'status' => $request->status,
            'action_reason' => $request->reason,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        // Notify the staff member
        // if($claim->user) $claim->user->notify(new \App\Notifications\ClaimProcessed($claim));

        return redirect()->back()->with('success', 'Claim status updated to ' . $request->status);
    }
}