<?php

namespace Database\Seeders;


use Illuminate\Support\Facades\Hash;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the user database.
     */
    public function run(): void
    {



        $user = User::factory()->create([
            'name' => 'Administrator',
            'email' => env('SEED_ADMIN_USER', 'admin@example.com'),
            'password' => Hash::make(env('SEED_ADMIN_PASSWORD', 'change_me_now'))
        ]);

        $user->assignRole('admin');
        $user->save();
    }
}
