<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tier = \App\Models\PricingTier::with('vehicles')->find(1);
echo "Tier: " . $tier->name . "\n";
foreach ($tier->vehicles as $v) {
    echo $v->plate_number . " - " . $v->status . "\n";
}
