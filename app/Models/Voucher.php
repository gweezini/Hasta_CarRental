<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    // These are the fields we allow to be saved
    protected $fillable = [
        'code',
        'name',
        'type',   // 'percent' or 'fixed'
        'value',  // e.g. 10.00
        'is_active',
    ];
}