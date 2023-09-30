<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Modules\Admin\Entities\SubscriptionPlan;

class StoreService
{
    function createFreeTrial($store)
    {
        $basicPlan = SubscriptionPlan::where('name', 'Basic')->first();
        $store->newSubscription('Basic', $basicPlan->stripe_plan_id)
            ->trialUntil(Carbon::now()->addDays(365))
            ->create();
    }
}
