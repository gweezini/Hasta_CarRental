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

        // Sync rules
        // Simplest strategy: delete allow and recreate, or update existing if IDs provided.
        // Given the requirement "edit the price, not empty", we want to keep the structure.
        // Let's assume the form submits existing rules.
        
        // Better strategy: iterate and update/create.
        // We will wipe and recreate to ensure clean slate if user adds/removes hours, 
        // OR precise update if we want to keep IDs.
        // Recreating is easier for "full list" editing.
        
        $pricing->rules()->delete();
        
        foreach ($request->rules as $ruleData) {
            $pricing->rules()->create($ruleData);
        }

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing rules updated successfully.');
    }
}
