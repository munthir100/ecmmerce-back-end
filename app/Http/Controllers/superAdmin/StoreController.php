<?php

namespace App\Http\Controllers\superAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Store\Entities\Store;

class StoreController extends Controller
{
    function index()
    {
        $stores = store::get();

        $storeList = $stores->map(function ($store) {
            $subscription = $store->subscription;

            // Calculate days remaining based on the subscription's trial end date
            $daysRemaining = $subscription ? now()->diffInDays($subscription->trial_ends_at) : null;

            return [
                'name' => $store->name,
                'link' => $store->link,
                'subscription_name' => $subscription ? $subscription->name : null,
                'subscription_days_remaining' => $daysRemaining,
            ];
        });

        return $storeList;
    }
}
