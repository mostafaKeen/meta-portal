<?php

namespace Modules\Plans\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Plans\Models\Plan;

class PlansDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Starter',
            'description' => 'Perfect for small teams getting started.',
            'price' => 29.00,
            'billing_cycle' => 'monthly',
            'max_qr_numbers' => 1,
            'max_agents' => 2,
            'max_session_messages' => 1000,
            'max_template_messages' => 50,
            'is_active' => true,
        ]);

        Plan::create([
            'name' => 'Professional',
            'description' => 'Optimized for growing businesses.',
            'price' => 79.00,
            'billing_cycle' => 'monthly',
            'max_qr_numbers' => 3,
            'max_agents' => 10,
            'max_session_messages' => 5000,
            'max_template_messages' => 500,
            'is_active' => true,
        ]);

        Plan::create([
            'name' => 'Enterprise',
            'description' => 'Unlimited possibilities for large organizations.',
            'price' => 199.00,
            'billing_cycle' => 'monthly',
            'max_qr_numbers' => 10,
            'max_agents' => 50,
            'max_session_messages' => 25000,
            'max_template_messages' => 2000,
            'is_active' => true,
        ]);
    }
}
