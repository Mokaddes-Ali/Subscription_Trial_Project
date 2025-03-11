<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'stripe_price_id',
        'trial_days',
        'amount',
        'type',
        'status',
        'slug',
        'created_at',
        'updated_at'
    ];
}
