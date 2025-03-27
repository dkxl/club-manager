<?php

namespace Database\Seeders;

use App\Models\CheckIn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


    /**
     * Seed the application's database with some default data for testing.
     */
    public function run(): void
    {

        $this->call([
            AdminSeeder::class,
            VenueSeeder::class,
            UserSeeder::class,
            InstructorSeeder::class,
            MemberSeeder::class,
            MembershipPlanSeeder::class,
            CheckInSeeder::class,
        ]);

    }
}
