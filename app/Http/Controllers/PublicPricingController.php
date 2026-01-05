<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PricingTier;

class PublicPricingController extends Controller
{
    public function index()
    {
        $tiers = PricingTier::with('rules')->get();
        return view('pricing.index', compact('tiers'));
    }
}
