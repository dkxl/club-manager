<?php

namespace Database\Seeders;


use App\Models\MembershipPlan;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    /**
     * Seed the user database.
     */
    public function run(): void
    {

        MembershipPlan::create([
            'name' => '12 Months Full',
            'free_classes' => true,
            'available' => true,
            'jf_amount' => 10.00,  // currency
            'puf_amount' => 29.99,
            'dd_amount' => 29.99,
            'term_months' => 12,
            'start_time' => '05:00:00',
            'end_time' => '23:00:00',
        ]);

        MembershipPlan::create([
            'name' => 'Monthly No Classes',
            'free_classes' => false,
            'available' => true,
            'jf_amount' => 10.00,  // currency
            'puf_amount' => 19.99,
            'dd_amount' => 19.99,
            'term_months' => 1,
            'start_time' => '05:00:00',
            'end_time' => '23:00:00',
        ]);



    }
}
