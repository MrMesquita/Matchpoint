<?php

use App\Models\Court;
use App\Models\Customer;

beforeEach(function() {
    $this->customerUser = Customer::factory()->create();
});

describe('fetch all courts', function() {
    test('can fetch all courts', function () {
        Court::factory()->count(3)->create();
        $response = $this->actingAs($this->customerUser)->getJson(route('courts.index'));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toHaveCount(3);

        expect($response->json('results'))
        ->each()
        ->toHaveKeys(['id','name','capacity','arena_id']);
    });

    test('try to get arenas without logged in', function() {
        $response = $this->getJson(route('courts.index'));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test('when there are no courts', function() {
        $response = $this->actingAs($this->customerUser)->getJson(route('courts.index'));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toHaveCount(0);
    });
});