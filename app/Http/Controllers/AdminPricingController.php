<?php

namespace App\Http\Controllers;

use App\Models\PricingTier;
use App\Models\PricingRule;
use Illuminate\Http\Request;

class AdminPricingController extends Controller
{
    public function index()
    {
        $tiers = PricingTier::with('rules')->get();
        return view('admin.pricing.index', compact('tiers'));
    }

    public function edit(PricingTier $pricing)
    {
        // $pricing corresponds to the 'pricing' parameter in route resource, which binds to PricingTier
        // Route parameter name for resource 'admin.pricing' is typically 'pricing'
        
        $pricing->load('rules');
        return view('admin.pricing.edit', compact('pricing'));
    }

    public function update(Request $request, PricingTier $pricing)
    {
        $request->validate([
            'rules' => 'required|array',
            'rules.*.hour_limit' => 'required|integer|min:1',
            'rules.*.price' => 'required|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($pricing, $request) {
            $pricing->rules()->delete();
            
            foreach ($request->rules as $rule) {
                $pricing->rules()->create([
                    'hour_limit' => $rule['hour_limit'],
                    'price' => $rule['price'],
                ]);
            }
        });

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing rules updated successfully.');
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
            'rates' => 'required|array|min:1',
            'rates.*.hour_limit' => 'required|integer|min:1',
            'rates.*.price' => 'required|numeric|min:0',
        ]);

        $tier = PricingTier::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        foreach ($request->rates as $rate) {
            $tier->rules()->create([
                'hour_limit' => $rate['hour_limit'],
                'price' => $rate['price'],
            ]);
        }

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing tier created successfully.');
    }
}
