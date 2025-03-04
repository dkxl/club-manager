<?php

namespace Database\Seeders;

use App\Models\CheckIn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            AdminSeeder::class,
            VenueSeeder::class,
            InstructorSeeder::class,
            MemberSeeder::class,
            MembershipPlanSeeder::class,
            CheckInSeeder::class,
        ]);

    }
}
