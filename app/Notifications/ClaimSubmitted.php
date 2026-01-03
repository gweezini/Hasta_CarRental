<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClaimSubmitted extends Notification
{
    use Queueable;

    public $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'claim_id' => $this->claim->id,
            'staff_name' => $this->claim->user->name,
            'amount' => $this->claim->amount,
            'type' => $this->claim->claim_type,
            'message' => 'New claim RM' . number_format($this->claim->amount, 2) . ' submitted by ' . $this->claim->user->name . '.',
            'url' => route('admin.claims.index'),
            'category' => 'claim_submission'
        ];
    }
}
