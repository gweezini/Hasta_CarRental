<?php

namespace App\Http\Controllers;

use App\Models\PricingTier;
use App\Models\PricingRate;
use Illuminate\Http\Request;

class AdminPricingController extends Controller
{
    public function index()
    {
        $tiers = PricingTier::with('rates', 'vehicles')->get();
        return view('admin.pricing.index', compact('tiers'));
    }

    public function create()
    {
        return view('admin.pricing.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rates' => 'required|array',
            'rates.*.hour_limit' => 'required|integer|min:1',
            'rates.*.price' => 'required|numeric|min:0',
        ]);

        $tier = PricingTier::create($request->only('name', 'description'));

        foreach ($request->rates as $rateData) {
            $tier->rates()->create($rateData);
        }

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing tier created successfully.');
    }

    public function edit(PricingTier $pricingTier)
    {
        $pricingTier->load('rates');
        return view('admin.pricing.edit', compact('pricingTier'));
    }

    public function update(Request $request, PricingTier $pricingTier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rates' => 'array',
            'rates.*.id' => 'nullable|exists:pricing_rates,id',
            'rates.*.hour_limit' => 'required|integer|min:1',
            'rates.*.price' => 'required|numeric|min:0',
        ]);

        $pricingTier->update($request->only('name', 'description'));

        // Handle rates update/create/delete logic could be complex
        // For simplicity, we'll iterate and update or create
        // A full implementation might sync or delete missing ones. 
        // Let's implement a simpler sync approach: Delete all and recreate? 
        // No, that destroys history if we track it. 
        // Let's just update existing and create new.
        
        // Better: user submits a list of rates.
        // We can just wipe and recreate for this simple use case, 
        // OR update where hour_limit matches?
        
        // Let's assume the form sends a complete list of rates desired.
        
        $pricingTier->rates()->delete(); 
        foreach ($request->rates as $rateData) {
            $pricingTier->rates()->create([
                'hour_limit' => $rateData['hour_limit'],
                'price' => $rateData['price']
            ]);
        }

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing tier updated successfully.');
    }
}
