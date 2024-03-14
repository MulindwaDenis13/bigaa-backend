<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    protected $fillable = [
        'unique_id', 'coupon_name', 'percent_off', 'max_redemptions', 'coupon_id', 'times_used', 'created_at'
    ];

    protected $casts = [
        'percent_off' => 'integer',
        'max_redemptions' => 'integer',
        'times_used' => 'integer',
        'created_at' => 'datetime',
    ];
}





