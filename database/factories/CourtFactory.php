<?php

namespace Database\Factories;

use App\Models\Arena;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Court>
 */
class CourtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Court '. $this->faker->randomNumber(2),
            'capacity' => 8,
            'arena_id' => Arena::factory()
        ];
    }
}
