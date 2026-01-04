<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with(['booking.user', 'booking.vehicle'])
            ->latest()
            ->paginate(10);

        return view('admin.feedbacks.index', compact('feedbacks'));
    }
}
