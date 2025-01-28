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
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->adminUser = Admin::factory()->create();
    $this->customerUser = Customer::factory()->create();
    $this->reservation = Reservation::factory()->create();

    $court = Court::factory()->create();
    $courtTimetable = CourtTimetable::factory()->create();

    $this->reservationData = [
        "customer_id" => $this->customerUser->id,
        "court_id" => $court->id,
        "court_timetable_id" => $courtTimetable->id
    ];
});

describe("store reservations", function () {
    test("customer can make a reservation", function () {
        $response = $this->actingAs($this->customerUser)->postJson(route("reservations.store"), $this->reservationData);

        checkCreatedCase($response);
        expect($response->json('results')[0])->toMatchArray($this->reservationData);
        expect(
            Reservation::where('customer_id', $this->reservationData['customer_id'])
                ->where('court_id', $this->reservationData['court_id'])
                ->where('court_timetable_id', $this->reservationData['court_timetable_id'])->exists()
        )->toBeTrue();
    });

    test("customer tries to send a customer_id different from his own", function () {
        $customer = Customer::factory()->create();
        $this->reservationData['customer_id'] = $customer->id;
        $response = $this->actingAs($this->customerUser)->postJson(route("reservations.store"), $this->reservationData);

        checkValidationErrorCase($response);
    });

    test("customer tries to book busy time", function () {
        $reservation = Reservation::factory()->create($this->reservationData);
        $reservation->update(['status' => ReservationStatus::CONFIRMED]);
        $reservation->courtTimetable->update(['status' => CourtTimetableStatus::BUSY]);

        $response = $this->actingAs($this->customerUser)->postJson(route("reservations.store"), $this->reservationData);
        checkValidationErrorCase($response);
    });

    test("tries to send a customer_id that doesn't exist", function () {
        $this->reservationData['customer_id'] = 0;
        $response = $this->actingAs($this->customerUser)->postJson(route("reservations.store"), $this->reservationData);
        checkNotFoundCase($response);
    });

    test("tries to send a court_id that doesn't exist", function () {
        $this->reservationData['court_id'] = 0;
        $response = $this->actingAs($this->customerUser)->postJson(route("reservations.store"), $this->reservationData);
        checkNotFoundCase($response);
    });

    test("tries to send a court_timetable_id that doesn't exist", function () {
        $this->reservationData['court_timetable_id'] = 0;
        $response = $this->actingAs($this->customerUser)->postJson(route("reservations.store"), $this->reservationData);
        checkNotFoundCase($response);
    });
});
