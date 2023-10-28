<?php

namespace Modules\Admin\Database\Seeders;


use Illuminate\Database\Seeder;
use App\Services\Admin\SubscriptionPlanService;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        // Initialize the SubscriptionPlanService
        $subscriptionPlanService = new SubscriptionPlanService();

        // Get the subscription plans data from your service
        $plans = $subscriptionPlanService->getPlans();

        // Create the plans using the SubscriptionPlanService
        $subscriptionPlanService->createPlans($plans);

        $this->command->info('Subscription plans seeded successfully.');
    }
}
