<?php

use App\Models\Arena;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->customerUser = Customer::factory()->count(1)->create()->first();
    $this->arena = Arena::factory()->count(1)->create()->first();
});

describe('fetch an arena by id', function() {
    test('is it possible to get an arena', function () {        
        $response = $this->actingAs($this->customerUser)->getJson(route('arenas.show', ['arena' => $this->arena->id]));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toBeGreaterThanOrEqual(1);
        expect($response->json('results.0'))
        ->toMatchArray([
            'id' => $this->arena->id,
            'name' => $this->arena->name,
            'street' => $this->arena->street,
            'number' => $this->arena->number,
            'neighborhood' => $this->arena->neighborhood,
            'city' => $this->arena->city,
            'state' => $this->arena->state,
            'zip_code' => $this->arena->zip_code,
            'admin_id' => $this->arena->admin_id
        ]);
    });

    test('try to get an arena without logged in', function() {
        $response = $this->getJson(route('arenas.show', ['arena' => $this->arena->id]));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to get an arena that doesn't exist", function() {
        $response = $this->actingAs($this->customerUser)->getJson(route('arenas.show', ['arena' => 0]));

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});