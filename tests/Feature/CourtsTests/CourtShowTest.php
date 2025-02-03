<?php

use App\Models\Court;
use App\Models\Customer;

beforeEach(function() {
    $this->customerUser = Customer::factory()->create();
    $this->court = Court::factory()->create();
});

describe('fetch a court by id', function() {
    test('is it possible to get a court', function () {        
        $response = $this->actingAs($this->customerUser)->getJson(route('courts.show', ['court' => $this->court->id]));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toBeGreaterThanOrEqual(1);
        expect($response->json('results.0'))
        ->toMatchArray([
            'id' => $this->court->id,
            'name' => $this->court->name,
            'capacity' => $this->court->capacity,
            'arena_id' => $this->court->arena_id
        ]);
    });

    test('try to get a court without logged in', function() {
        $response = $this->getJson(route('courts.show', ['court' => $this->court->id]));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to get a court that doesn't exist", function() {
        $response = $this->actingAs($this->customerUser)->getJson(route('courts.show', ['court' => 0]));

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});