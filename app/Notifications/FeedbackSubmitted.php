<?php

namespace App\Notifications;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FeedbackSubmitted extends Notification
{
    use Queueable;

    protected $feedback;

    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $customer = $this->feedback->booking->user->name;

        return [
            'feedback_id' => $this->feedback->id,
            'message' => "⭐ New Feedback from {$customer} for Booking #{$this->feedback->booking_id}.",
            'category' => 'feedback_submission',
            'url' => route('admin.feedbacks.index'),
        ];
    }
}
