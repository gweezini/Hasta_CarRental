<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClaimProcessed extends Notification
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
        $statusLabel = $this->claim->status === 'Approved' ? '✅ APPROVED' : '❌ REJECTED';
        
        return [
            'claim_id' => $this->claim->id,
            'status' => $this->claim->status,
            'amount' => $this->claim->amount,
            'message' => 'Your claim RM' . number_format($this->claim->amount, 2) . ' has been ' . $statusLabel . '.',
            'reason' => $this->claim->action_reason,
            'has_receipt' => !empty($this->claim->receipt_path),
            'url' => route('admin.claims.create'), // Staff see history on submission page
            'category' => 'claim_update'
        ];
    }
}
