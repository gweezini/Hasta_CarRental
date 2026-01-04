<?php

use Illuminate\Support\Facades\Schema;

require __DIR__.'/bootstrap/app.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = ['refund_bank_name', 'refund_account_number', 'refund_recipient_name'];
$missing = [];

foreach ($columns as $col) {
    if (!Schema::hasColumn('bookings', $col)) {
        $missing[] = $col;
    }
}

if (empty($missing)) {
    echo "ALL_COLUMNS_EXIST";
} else {
    echo "MISSING: " . implode(', ', $missing);
}
