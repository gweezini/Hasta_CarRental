<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with(['booking.user', 'booking.vehicle'])->latest();

        if ($request->filled('maintenance')) {
            if ($request->maintenance === 'flagged') {
                $query->where(function($q) {
                    $q->where('ratings->issue_interior', true)
                      ->orWhere('ratings->issue_smell', true)
                      ->orWhere('ratings->issue_mechanical', true)
                      ->orWhere('ratings->issue_ac', true)
                      ->orWhere('ratings->issue_exterior', true)
                      ->orWhere('ratings->issue_safety', true);
                });
            } elseif ($request->maintenance === 'none') {
                $query->whereNot(function($q) {
                    $q->where('ratings->issue_interior', true)
                      ->orWhere('ratings->issue_smell', true)
                      ->orWhere('ratings->issue_mechanical', true)
                      ->orWhere('ratings->issue_ac', true)
                      ->orWhere('ratings->issue_exterior', true)
                      ->orWhere('ratings->issue_safety', true);
                });
            }
        }

        $feedbacks = $query->paginate(10)->withQueryString();

        return view('admin.feedbacks.index', compact('feedbacks'));
    }
}
