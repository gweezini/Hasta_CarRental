<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InspectionController extends Controller
{
    /**
     * Show the form for creating a new inspection.
     */
    public function create(Request $request, Booking $booking)
    {
        // Ensure user can inspect this booking.
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'Approved') {
            abort(403, 'You can only inspect approved bookings.'); 
        }
        
        $type = $request->query('type', 'pickup');

        return view('inspections.create', compact('booking', 'type'));
    }

    /**
     * Store a newly created inspection in storage.
     */
    public function store(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'Approved') {
            abort(403, 'You can only inspect approved bookings.'); 
        }

        $request->validate([
            'type' => 'required|in:pickup,return',
            'fuel_level' => 'required|integer|min:0|max:10',
            'mileage' => 'required|integer|min:0',
            'remarks' => 'nullable|string',
            'photo_front' => 'required|image|max:10240',
            'photo_dashboard' => 'required|image|max:10240',
            'photo_back' => $request->type === 'pickup' ? 'required|image|max:10240' : 'nullable|image|max:10240',
            'photo_left' => $request->type === 'pickup' ? 'required|image|max:10240' : 'nullable|image|max:10240',
            'photo_right' => $request->type === 'pickup' ? 'required|image|max:10240' : 'nullable|image|max:10240',
            'photo_keys' => $request->type === 'return' ? 'required|image|max:10240' : 'nullable',
            'damage_photos.*' => 'image|max:10240',
            'damage_description' => 'nullable|string',
            'acknowledgement' => 'accepted',
            'agreement_check' => $request->type === 'pickup' ? 'accepted' : 'nullable',
            
            // Checklist Feedback fields
            'issue_interior' => 'nullable',
            'issue_smell' => 'nullable',
            'issue_mechanical' => 'nullable',
            'issue_ac' => 'nullable',
            'issue_exterior' => 'nullable',
            'issue_safety' => 'nullable',
            'feedback_text' => 'nullable|string',
        ]);

        $data = [
            'booking_id' => $booking->id,
            'type' => $request->type,
            'fuel_level' => $request->fuel_level,
            'mileage' => $request->mileage,
            'remarks' => $request->remarks,
            'damage_description' => $request->damage_description,
            'created_by' => auth()->id(),
        ];

        // Handle File Uploads
        $sides = ['front', 'back', 'left', 'right', 'dashboard', 'keys'];
        foreach ($sides as $side) {
            if ($request->hasFile("photo_{$side}")) {
                $data["photo_{$side}"] = $request->file("photo_{$side}")->store('inspections', 'public');
            }
        }

        if ($request->hasFile('damage_photos')) {
            $damagePhotos = [];
            foreach ($request->file('damage_photos') as $photo) {
                $damagePhotos[] = $photo->store('inspections', 'public');
            }
            $data['damage_photos'] = $damagePhotos;
        }

        Inspection::create($data);

        // Store Feedback and handle fuel/maintenance if Return
        if ($request->type === 'return') {
            // Fuel Penalty Logic: RM 10 per missing bar
            $pickupInspection = $booking->inspections()->where('type', 'pickup')->first();
            if ($pickupInspection && $request->fuel_level < $pickupInspection->fuel_level) {
                $missingBars = $pickupInspection->fuel_level - $request->fuel_level;
                $penaltyAmount = $missingBars * 10;
                
                \App\Models\Fine::create([
                    'booking_id' => $booking->id,
                    'amount' => $penaltyAmount,
                    'reason' => "Fuel penalty: {$missingBars} bars missing (Pickup: {$pickupInspection->fuel_level}, Return: {$request->fuel_level})",
                    'status' => 'Unpaid',
                ]);
            }

            // Auto Update Vehicle Fuel
            if ($booking->vehicle) {
                $booking->vehicle->update(['current_fuel_bars' => $request->fuel_level]);
            }

            \App\Models\Feedback::create([
                'booking_id' => $booking->id,
                'category' => 'Return Inspection',
                'description' => $request->feedback_text,
                'ratings' => [
                    'issue_interior' => $request->has('issue_interior'),
                    'issue_smell' => $request->has('issue_smell'),
                    'issue_mechanical' => $request->has('issue_mechanical'),
                    'issue_ac' => $request->has('issue_ac'),
                    'issue_exterior' => $request->has('issue_exterior'),
                    'issue_safety' => $request->has('issue_safety'),
                ]
            ]);

            // Auto-update vehicle status if ANY issue is reported
            $hasAnyIssue = $request->hasAny(['issue_interior', 'issue_smell', 'issue_mechanical', 'issue_ac', 'issue_exterior', 'issue_safety']);
            if ($hasAnyIssue && $booking->vehicle) {
                $booking->vehicle->update(['status' => 'Unavailable']);
                
                // Notify all staff about maintenance need
                $staff = \App\Models\User::whereIn('role', ['admin', 'topmanagement'])->get();
                \Illuminate\Support\Facades\Notification::send($staff, new \App\Notifications\VehicleMaintenanceAlert($booking->vehicle, $booking));
            }

            // Always notify staff about new inspection
            $staff = \App\Models\User::whereIn('role', ['admin', 'topmanagement'])->get();
            $inspection = \App\Models\Inspection::latest()->first(); // The one we just created
            \Illuminate\Support\Facades\Notification::send($staff, new \App\Notifications\InspectionSubmitted($inspection));

            // If return, feedback was also created
            if ($request->type === 'return') {
                $feedback = \App\Models\Feedback::latest()->first();
                \Illuminate\Support\Facades\Notification::send($staff, new \App\Notifications\FeedbackSubmitted($feedback));
            }
        }

        return redirect()->route('inspections.show', Inspection::latest()->first())
            ->with('success', 'Inspection recorded successfully.');
    }

    /**
     * Display the specified inspection.
     */
    public function show(Inspection $inspection)
    {
        return view('inspections.show', compact('inspection'));
    }
}
