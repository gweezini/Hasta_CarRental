<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FineController extends Controller
{
    /**
     * Handle receipt upload by customer.
     */
    public function uploadReceipt(Request $request, $id)
    {
        $fine = Fine::findOrFail($id);

        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($fine->receipt_path) {
                Storage::disk('public')->delete($fine->receipt_path);
            }

            $path = $request->file('receipt')->store('receipts/fines', 'public');
            
            $fine->update([
                'receipt_path' => $path,
                'status' => 'Pending Verification'
            ]);

            return back()->with('success', 'Receipt uploaded successfully! Please wait for admin verification.');
        }

        return back()->with('error', 'Please select a valid image file.');
    }

    /**
     * Admin verifies method.
     */
    public function verify($id)
    {
        $fine = Fine::findOrFail($id);
        
        $fine->update([
            'status' => 'Paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Fine marked as PAID.');
    }

    /**
     * Replaces AdminController payFine if needed, can be same as verify or distinct if just marking paid without receipt.
     */
    public function pay($id)
    {
        return $this->verify($id);
    }
}
