<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->unique()->firstName(),
            'last_name' => fake()->unique()->lastName(),
            'dob' => fake()->date(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'card_number' => fake()->unique()->numberBetween(10000000, 99999999),
        ];
    }
}
