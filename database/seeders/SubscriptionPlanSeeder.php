<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        SubscriptionPlan::insert([
            [
                'name' => 'Monthly',
                'stripe_price_id' => 'price_1R1MlUP4yOGEaJyLAnvKJgQp',
                'trial_days' => 5,
                'amount' => 50.00,
                'type' => 0,
                'status' => 1,
                'slug' => 'monthly123',
                'created_at' => $currentDateTime,
                'updated_at' => $currentDateTime
            ],

            [
                'name' => 'Yearly',
                'stripe_price_id' => 'price_1R1MmQP4yOGEaJyLLWmdJnEt',
                'trial_days' => 5,
                'amount' => 150.00,
                'type' => 1,
                'status' => 1,
                'slug' => 'yearly123',
                'created_at' => $currentDateTime,
                'updated_at' => $currentDateTime
            ],

            [
                'name' => 'Lifetime',
                'stripe_price_id' => 'price_1R1Mn5P4yOGEaJyLmKnp5eRp',
                'trial_days' => 5,
                'amount' => 500.00,
                'type' => 2,
                'status' => 1,
                'slug' => 'lifetime123',
                'created_at' => $currentDateTime,
                'updated_at' => $currentDateTime
            ]
        ]);


        // foreach ($plans as $plan) {
        //     SubscriptionPlan::create($plan);
        // }
    }
}
