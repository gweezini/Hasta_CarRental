<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Voucher;
use App\Models\UserVoucher;

class UserVoucherController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vouchers = $user->userVouchers()->orderBy('created_at', 'desc')->get();
        return view('vouchers.index', compact('vouchers'));
    }

    public function redeemCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $code = strtoupper($request->code);
        
        // 1. Find the voucher master record
        $voucher = Voucher::where('code', $code)->where('is_active', true)->first();

        // 2. Validation
        if (!$voucher) {
            return redirect()->back()->with('error', 'Invalid or inactive code.');
        }

        // 3. User & Duplicate Check
        $user = Auth::user();

        // Check availability (uses_remaining)
        if ($voucher->single_use && $voucher->uses_remaining !== null && $voucher->uses_remaining <= 0) {
            return redirect()->back()->with('error', 'This voucher has been fully redeemed.');
        }

        // Check if user already owns it
        if ($user->userVouchers()->where('voucher_id', $voucher->id)->exists()) {
            return redirect()->back()->with('error', 'You have already redeemed this voucher.');
        }

        // 4. Assign Voucher (Pivot Creation)
        UserVoucher::create([
            'user_id' => $user->matric_staff_id, // Use correct FK
            'voucher_id' => $voucher->id,
            'used_at' => null
        ]);

        // 5. Deduct Global Usage if applicable
        if ($voucher->single_use && $voucher->uses_remaining !== null) {
             $voucher->decrement('uses_remaining');
        }

        return redirect()->route('profile.edit', ['tab' => 'rewards'])->with('success', 'Voucher redeemed successfully!');
    }

    public function redeemLoyalty(Request $request)
    {
        $request->validate(['tier' => 'required|in:3,6,9,12,15']);
        $tier = (int)$request->tier;
        $user = Auth::user();
        $card = $user->loyaltyCard;
        if (!$card || $card->stamps < $tier) return redirect()->back()->with('error', 'Not enough stamps');

        // map tiers
        $map = [
            3 => ['type' => 'percent', 'value' => 10, 'name' => 'Loyalty 10% (3 stamps)'],
            6 => ['type' => 'percent', 'value' => 15, 'name' => 'Loyalty 15% (6 stamps)'],
            9 => ['type' => 'percent', 'value' => 20, 'name' => 'Loyalty 20% (9 stamps)'],
            12 => ['type' => 'percent', 'value' => 25, 'name' => 'Loyalty 25% (12 stamps)'],
            15 => ['type' => 'free_hours', 'value' => 12, 'name' => 'Loyalty 12 hours free (15 stamps)'],
        ];

        $info = $map[$tier];

        UserVoucher::create([
            'user_id' => $user->id,
            'code' => null,
            'name' => $info['name'],
            'type' => $info['type'],
            'value' => $info['value'],
            'source' => 'loyalty',
            'is_active' => true,
        ]);

        // deduct stamps
        $card->stamps -= $tier;
        $card->save();

        return redirect()->back()->with('success', 'Loyalty reward created');
    }
}
