<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'interval',
        'interval_count',
        'amount',
        'stripe_plan_id',
        'stripe_plan_name',
    ];
    

}
