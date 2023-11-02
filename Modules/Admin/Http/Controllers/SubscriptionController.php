<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\Admin\SubscriptionPlanService;
use Stripe\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin\Entities\SubscriptionPlan;

class SubscriptionController extends Controller
{
    protected $subscriptionPlanService;
    function __construct()
    {
        $this->subscriptionPlanService = new SubscriptionPlanService();
    }
    function createPlans()
    {
        $plans = $this->subscriptionPlanService->getPlans();
        $this->subscriptionPlanService->createPlans($plans);

        return $this->responseCreated('plans created');
    }

    function index()
    {
        $plans = SubscriptionPlan::all();

        return $plans;
    }
}
