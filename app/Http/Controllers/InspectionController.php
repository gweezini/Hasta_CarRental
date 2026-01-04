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
            'photo_front' => 'required|image|max:2048',
            'photo_dashboard' => 'required|image|max:2048',
            'photo_back' => $request->type === 'pickup' ? 'required|image|max:2048' : 'nullable|image|max:2048',
            'photo_left' => $request->type === 'pickup' ? 'required|image|max:2048' : 'nullable|image|max:2048',
            'photo_right' => $request->type === 'pickup' ? 'required|image|max:2048' : 'nullable|image|max:2048',
            'damage_photos.*' => 'image|max:2048',
            'damage_description' => 'nullable|string',
            'acknowledgement' => 'accepted',
            'agreement_check' => $request->type === 'pickup' ? 'accepted' : 'nullable',
            
            // Check for Feedback fields if type is return
            'rating_cleanliness_interior' => $request->type === 'return' ? 'required|integer|between:1,5' : 'nullable',
            'rating_smell' => $request->type === 'return' ? 'required|string' : 'nullable',
            'rating_cleanliness_exterior' => $request->type === 'return' ? 'required|integer|between:1,5' : 'nullable',
            'rating_cleanliness_trash' => $request->type === 'return' ? 'required|integer|between:1,5' : 'nullable',
            'rating_condition_mechanical' => $request->type === 'return' ? 'required|integer|between:1,5' : 'nullable',
            'rating_condition_ac' => $request->type === 'return' ? 'required|integer|between:1,5' : 'nullable',
            'rating_condition_fuel' => $request->type === 'return' ? 'required|integer|between:1,5' : 'nullable',
            'rating_condition_safety' => $request->type === 'return' ? 'required|integer|between:1,5' : 'nullable',
            'rating_service_access' => $request->type === 'return' ? 'required|integer|between:1,5' : 'nullable',
            'rating_service_value' => $request->type === 'return' ? 'required|integer|between:1,5' : 'nullable',
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

        // Store Feedback if Return
        if ($request->type === 'return') {
            \App\Models\Feedback::create([
                'booking_id' => $booking->id,
                'category' => 'Return Inspection',
                'description' => $request->feedback_text,
                'ratings' => [
                    'cleanliness_interior' => $request->rating_cleanliness_interior,
                    'smell' => $request->rating_smell,
                    'cleanliness_exterior' => $request->rating_cleanliness_exterior,
                    'cleanliness_trash' => $request->rating_cleanliness_trash,
                    'condition_mechanical' => $request->rating_condition_mechanical,
                    'condition_ac' => $request->rating_condition_ac,
                    'condition_fuel' => $request->rating_condition_fuel,
                    'condition_safety' => $request->rating_condition_safety,
                    'service_access' => $request->rating_service_access,
                    'service_value' => $request->rating_service_value,
                ]
            ]);
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
