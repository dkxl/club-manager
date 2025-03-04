<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Venue;


class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Venue::factory(10)->create();

        $venues = ['Studio 1', 'Studio 2', 'Studio 3'];

        foreach ($venues as $venue) {
            Venue::factory()->create([
                'name' => $venue
            ]);
        }
    }
}
