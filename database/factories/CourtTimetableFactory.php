<?php

namespace Database\Factories;

use App\Enums\CourtTimetableStatus;
use App\Models\Court;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourtTimetable>
 */
class CourtTimetableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->time('H:i');
        $endTime = $this->faker->time('H:i', strtotime('+2 hours', strtotime($startTime)));

        return [
            "day_of_week" => $this->faker->numberBetween(0, 6),
            "start_time" => $startTime,
            "end_time" => $endTime,
            "status" => CourtTimetableStatus::AVAILABLE,
            "court_id" => Court::factory(),
        ];
    }
}
