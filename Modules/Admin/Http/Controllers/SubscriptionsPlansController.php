<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\SubscriptionPlan;
use Modules\Admin\Transformers\SubscriptionsPlansResource;

class SubscriptionsPlansController extends Controller
{

    public function index()
    {
        $subscriptionsPlans = SubscriptionPlan::all();

        return SubscriptionsPlansResource::collection($subscriptionsPlans);
    }


    public function show(SubscriptionPlan $subscriptionsPlan)
    {
        return new SubscriptionsPlansResource($subscriptionsPlan);
    }

    public function upgrade(){
        
    }

}
