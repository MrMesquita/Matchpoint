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
    $this->customer = Customer::factory()->create();
});

describe("fetch all reservations", function() {
    test("system can list all reservations", function() {
        Reservation::factory()->count(3)->create();
        $response = $this->actingAs($this->systemUser)->getJson(route('reservations.index'));

        checkSuccessCase($response, 3);
        checkReservationsResults($response->json('results'));
    });

    test("admin can only list his reservations", function() {
        $arena = Arena::factory()->create(['admin_id' => $this->adminUser->id]);
        $court = Court::factory()->create(['arena_id' => $arena->id]);
        
        Reservation::factory()->count(3)->create(['court_id' => $court->id]);
        Reservation::factory()->count(3)->create();
        
        $response = $this->actingAs($this->adminUser)->getJson(route('reservations.index'));
        checkSuccessCase($response, 3);
        checkReservationsResults($response->json('results'));
    });

    test("customer can only list his reservations", function() {
        Reservation::factory()->count(3)->create(['customer_id' => $this->customer->id]);
        Reservation::factory()->count(3)->create();
        
        $response = $this->actingAs($this->customer)->getJson(route('reservations.index'));
    
        checkSuccessCase($response, 3);
        checkReservationsResults($response->json('results'));
    });
    

    test('try to get a reservation without logged in', function() {
        $response = $this->getJson(route('reservations.index'));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test('when there are no reservations', function() {
        $response = $this->actingAs($this->systemUser)->getJson(route('reservations.index'));
        checkSuccessCase($response, 0);
    });
});