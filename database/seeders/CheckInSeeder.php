<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CheckIn;
use App\Models\Member;


class CheckInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $members = Member::factory()->count(10)->create();

        foreach ($members as $member) {
            CheckIn::create([
                'member_id' => $member->id,
                'card_number' => $member->card_number,
                'permitted' => fake()->boolean(75),
                'reason' => 'Testing',
            ]);
        }
    }
}
