<?php

use App\Models\Arena;
use App\Models\Court;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->customerUser = Customer::factory()->count(1)->create()->first();
    $this->arena = Arena::factory()->count(1)->create()->first();
});

describe('fetch the arena courts', function() {
    test('it is possible to take the courts', function () {        
        Court::factory()->count(3)->create(['arena_id' => $this->arena->id]);
        $response = $this->actingAs($this->customerUser)->getJson(route('arenas.courts', ['arena' => $this->arena->id]));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toBeGreaterThanOrEqual(3);

        expect($response->json('results'))
        ->each()
        ->toHaveKeys(['id','name','capacity','arena_id']);

        expect($response->json('results'))->each()->toMatchArray(["arena_id" => $this->arena->id]);
    });

    test('trying to get courts from an arena without being logged in', function() {
        $response = $this->getJson(route('arenas.courts', ['arena' => $this->arena->id]));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("try to get courts from an arena that don't exist", function() {
        $response = $this->actingAs($this->customerUser)->getJson(route('arenas.courts', ['arena' => 0]));

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("trying to get courts from an arena that doesn't have any courts", function() {
        $response = $this->actingAs($this->customerUser)->getJson(route('arenas.courts', ['arena' => $this->arena->id]));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toHaveCount(0);
    });
});