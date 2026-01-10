<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->get();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:vouchers,code',
            'name' => 'required|string',
            'type' => 'required|string',
            'value' => 'required|numeric',
            'single_use' => 'sometimes|boolean',
            'uses_remaining' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $data['single_use'] = $request->has('single_use') ? (bool)$request->single_use : true;
        if (!isset($data['uses_remaining']) && $data['single_use']) {
            $data['uses_remaining'] = 1;
        }

        Voucher::create(array_merge($data, ['is_active' => true]));

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher created');
    }

    public function show(Voucher $voucher)
    {
        $redeemers = \App\Models\UserVoucher::with('user')
            ->where('voucher_id', $voucher->id)
            ->whereNotNull('used_at')
            ->orderBy('used_at', 'desc')
            ->get();
            
        return view('admin.vouchers.show', compact('voucher', 'redeemers'));
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:vouchers,code,' . $voucher->id,
            'name' => 'required|string',
            'type' => 'required|string',
            'value' => 'required|numeric',
            'single_use' => 'sometimes|boolean',
            'uses_remaining' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'expires_at' => 'nullable|date',
        ]);

        $data['single_use'] = $request->has('single_use') ? (bool)$request->single_use : $voucher->single_use;

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher updated');
    }

    public function toggleStatus(Voucher $voucher)
    {
        $voucher->is_active = !$voucher->is_active;
        $voucher->save();

        $status = $voucher->is_active ? 'activated' : 'invalidated';
        return redirect()->back()->with('success', "Voucher was successfully {$status}.");
    }
}
