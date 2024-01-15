<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\SubscriptionPlan;
use Modules\Admin\Http\Requests\UpgradeSubscriptionPlanRequest;
use Modules\Admin\Transformers\SubscriptionsPlansResource;

class SubscriptionsPlansController extends Controller
{

    public function index()
    {
        $subscriptionsPlans = SubscriptionPlan::all();

        return $this->responseSuccess(data: [SubscriptionsPlansResource::collection($subscriptionsPlans)]);
    }


    public function show(SubscriptionPlan $subscriptionsPlan)
    {
        return new SubscriptionsPlansResource($subscriptionsPlan);
    }

    public function upgrade(UpgradeSubscriptionPlanRequest $request)
    {
        $data = $request->validated();
        $newPlan = SubscriptionPlan::findOrFail($data['subscription_plans']);
        $currentSubscription = $request->store->subscription;
        dd($currentSubscription->onTrial());
        if ($currentSubscription->active()) {
            dd($currentSubscription->name());
            // Swap the current subscription to the new plan
            $currentSubscription->swap($newPlan->stripe_plan_id);

            return response()->json(['message' => 'Subscription plan upgraded successfully']);
        } else {

            return response()->json(['message' => 'No active subscription found'], 422);
        }
    }
}
