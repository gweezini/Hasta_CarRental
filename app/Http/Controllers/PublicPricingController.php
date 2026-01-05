<?php

namespace App\Http\Controllers;

use App\Models\PricingTier;
use Illuminate\Http\Request;

class PublicPricingController extends Controller
{
    public function index()
    {
        $tiers = PricingTier::with('rates')->get();
        return view('pricing.index', compact('tiers'));
    }
}
