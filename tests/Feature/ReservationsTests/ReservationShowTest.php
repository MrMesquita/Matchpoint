<?php

use App\Models\Admin;
use App\Models\Arena;
use App\Models\Court;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class); 

beforeEach(function() {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->adminUser = Admin::factory()->create();
    $this->customerUser = Customer::factory()->create();
    $arena = Arena::factory()->create(['admin_id' => $this->adminUser->id]);
    $court = Court::factory()->create(['arena_id' => $arena->id]);
    $this->reservation = Reservation::factory()->create(['court_id' => $court->id]);
});

describe("fetch a reservation by id", function() {
    test("system can list a reservation", function() {
        $response = $this->actingAs($this->systemUser)->getJson(route('reservations.show', ['reservation' => $this->reservation->id]));

        checkSuccessCase($response, 1);
        checkReservationsResults($response->json('results'));
    });

    test("admin can only list his reservations", function() {
        $response = $this->actingAs($this->adminUser)->getJson(route('reservations.show', ['reservation' => $this->reservation->id]));

        checkSuccessCase($response, 1);
        checkReservationsResults($response->json('results'));
    });

    test("customer can only list his reservations", function() {
        $reservation = Reservation::factory()->create(['customer_id' => $this->customerUser->id]);
        $response = $this->actingAs($this->customerUser)->getJson(route('reservations.show', ['reservation' => $reservation->id]));

        checkSuccessCase($response, 1);
        checkReservationsResults($response->json('results'));
    });

    test('try to get a reservation without system logged in', function() {
        $response = $this->getJson(route('reservations.show', ['reservation' => $this->reservation->id]));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test('when there are no reservation', function() {
        $response = $this->actingAs($this->systemUser)->getJson(route('reservations.show', ['reservation' => 0]));

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});