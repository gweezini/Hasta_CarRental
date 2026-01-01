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
        $template = Voucher::where('code', $code)->where('is_active', true)->first();
        if (!$template) return redirect()->back()->with('error', 'Invalid code');

        // create a user-specific voucher
        UserVoucher::create([
            'user_id' => Auth::id(),
            'code' => $template->code,
            'name' => $template->name,
            'type' => $template->type,
            'value' => $template->value,
            'source' => 'code',
            'is_active' => true,
        ]);

        // honor template single-use / usage count
        if ($template->single_use) {
            if (is_null($template->uses_remaining) || $template->uses_remaining <= 1) {
                $template->is_active = false;
                $template->uses_remaining = 0;
            } else {
                $template->uses_remaining = max(0, $template->uses_remaining - 1);
            }
            $template->save();
        }

        return redirect()->back()->with('success', 'Code redeemed');
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
