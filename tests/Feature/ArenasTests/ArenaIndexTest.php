<?php

use App\Models\Arena;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->customerUser = Customer::factory()->count(1)->create()->first();
});

describe('fetch all arenas', function() {
    test('can fetch all arenas', function () {
        Arena::factory()->count(3)->create();
        $response = $this->actingAs($this->customerUser)->getJson(route('arenas.index'));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toHaveCount(3);

        expect($response->json('results'))
        ->each()
        ->toHaveKeys(['id','name','street','number','neighborhood','city','state','zip_code','admin_id']);
    });

    test('try to get arenas without logged in', function() {
        $response = $this->getJson(route('arenas.index'));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test('when there are no arenas', function() {
        $response = $this->actingAs($this->customerUser)->getJson(route('arenas.index'));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toHaveCount(0);
    });
});