<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tiers = \App\Models\PricingTier::with('vehicles', 'rules')->get();
foreach ($tiers as $tier) {
    echo "Tier: " . $tier->name . " (ID: " . $tier->id . ")\n";
    echo "Vehicles: " . $tier->vehicles->pluck('plate_number')->implode(', ') . "\n";
    foreach ($tier->rules as $rule) {
        echo "  " . $rule->hour_limit . "h -> " . $rule->price . "\n";
    }
    echo "--------------------------\n";
}
