<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClaimController extends Controller
{
    /**
     * Show form for staff to create a claim.
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        $myClaims = Claim::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
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
        ]);

        $claim = Claim::create([
            'user_id' => Auth::id(),
            'claim_type' => $request->claim_type,
            'vehicle_plate' => $request->vehicle_plate,
            'amount' => $request->amount,
            'claim_date_time' => Carbon::parse($request->claim_date . ' ' . $request->claim_time),
            'description' => $request->description,
            'status' => 'Pending',
        ]);

        // Notify Top Management
        $topManagement = \App\Models\User::whereIn('role', ['topmanagement', 'admin'])->get();
        \Illuminate\Support\Facades\Notification::send($topManagement, new \App\Notifications\ClaimSubmitted($claim));

        return redirect()->route('admin.dashboard')->with('success', 'Claim submitted successfully!');
    }

    /**
     * List claims for Top Management review.
     */
    public function index()
    {
        if (!Auth::user()->isTopManagement()) {
            return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access.');
        }

        $claims = Claim::with(['user', 'processor'])->orderBy('created_at', 'desc')->paginate(15);
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
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Mandatory bank receipt
        ]);

        $claim = Claim::findOrFail($id);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $imageName = time().'.'.$request->receipt->extension();  
            $request->receipt->move(public_path('images/receipts'), $imageName);
            $receiptPath = 'images/receipts/' . $imageName;
        }

        $claim->update([
            'status' => $request->status,
            'action_reason' => $request->reason,
            'receipt_path' => $receiptPath,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        // Notify the staff member who submitted the claim
        $claim->user->notify(new \App\Notifications\ClaimProcessed($claim));

        return redirect()->back()->with('success', 'Claim status updated to ' . $request->status . ' and staff notified.');
    }
}
