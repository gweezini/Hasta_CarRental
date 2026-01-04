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
    public function create(Booking $booking)
    {
        // Ensure user can inspect this booking.
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'Approved') {
            abort(403, 'You can only inspect approved bookings.'); 
        }
        
        return view('inspections.create', compact('booking'));
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
            'photo_front' => 'required|image|max:2048',
            'photo_back' => 'required|image|max:2048',
            'photo_left' => 'required|image|max:2048',
            'photo_right' => 'required|image|max:2048',
            'photo_dashboard' => 'required|image|max:2048',
            'damage_photos.*' => 'image|max:2048',
            'damage_description' => 'nullable|string',
            'acknowledgement' => 'accepted',
            'agreement_check' => 'accepted',
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
        $sides = ['front', 'back', 'left', 'right', 'dashboard'];
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
