<?php
namespace App\Services\Admin;

use Modules\Admin\Entities\SubscriptionPlan;
use Stripe\Plan;

class SubscriptionPlanService
{
    protected $currency = 'usd'; // Default currency

    function getPlans()
    {
        return [
            ['product_name' => 'Basic', 'interval' => 'year', 'amount' => 0],
            ['product_name' => 'Advanced', 'interval' => 'month', 'amount' => 4900],
            ['product_name' => 'Advanced', 'interval' => 'month', 'interval_count' => 3, 'amount' => 17400],
            ['product_name' => 'Advanced', 'interval' => 'month', 'interval_count' => 6, 'amount' => 29400],
            ['product_name' => 'Advanced', 'interval' => 'month', 'interval_count' => 12, 'amount' => 499900],
            ['product_name' => 'Pro', 'interval' => 'year', 'amount' => 119900],
        ];
    }

    function createPlans($plans)
    {
        foreach ($plans as $planData) {
            $stripePlan = $this->createStripePlan($planData);
            $this->savePlanInDatabase($stripePlan, $planData);
        }
    }

    protected function createStripePlan($planData)
    {
        return Plan::create([
            'currency' => $this->currency,
            'interval' => $planData['interval'],
            'interval_count' => $planData['interval_count'] ?? 1,
            'amount' => $planData['amount'],
            'product' => [
                'name' => $planData['product_name'],
            ],
        ]);
    }

    protected function savePlanInDatabase($stripePlan, $planData)
    {
        // Create a new subscription plan record in your database
        return SubscriptionPlan::create([
            'name' => $planData['product_name'],
            'stripe_plan_id' => $stripePlan->id,
            'stripe_plan_name' => $stripePlan->product, // Save the Stripe plan name
            'interval' => $stripePlan->interval,
            'interval_count' => $stripePlan->interval_count,
            'amount' => $stripePlan->amount,
        ]);
    }
    
}









