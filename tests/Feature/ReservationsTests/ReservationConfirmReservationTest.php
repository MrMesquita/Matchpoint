<?php

use App\Enums\CourtTimetableStatus;
use App\Enums\ReservationStatus;
use App\Models\Admin;
use App\Models\Arena;
use App\Models\Court;
use App\Models\CourtTimetable;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->adminUser = Admin::factory()->create();
    $customerUser = Customer::factory()->create();
    
    $arena = Arena::factory()->create(['admin_id' => $this->adminUser->id]);
    $court = Court::factory()->create(['arena_id' => $arena->id]);
    $courtTimetable = CourtTimetable::factory()->create(['court_id' => $court->id]);
    $reservationData = [
        "customer_id" => $customerUser->id,
        "court_id" => $court->id,
        "court_timetable_id" => $courtTimetable->id
    ];

    $this->reservation = Reservation::factory()->create($reservationData);

});

describe("confirm reservations", function () {
    test("admin can confirm a reservation", function () {
        $response = $this->actingAs($this->adminUser)->postJson(
            route('reservations.confirmReservation', 
            ['reservation' => $this->reservation->id])
        );

        checkSuccessCase($response);
        checkReservationsResults($response->json('results'));
    });
});