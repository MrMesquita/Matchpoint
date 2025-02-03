<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\Court;
use App\Models\CourtTimetable;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReservationFactory>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'court_id' => Court::factory(),
            'court_timetable_id' => CourtTimetable::factory(),
            'status' => ReservationStatus::PENDING
        ];
    }
}
