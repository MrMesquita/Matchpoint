<?php

use App\Models\Customer;
use App\Models\User;

beforeEach(function() {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
});

describe('fetch all customers', function() {
    test('can fetch all customers', function () {
        Customer::factory()->count(3)->create();
        $response = $this->actingAs($this->systemUser)->getJson(route('customers.index'));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toHaveCount(3);

        expect($response->json('results'))
        ->each()
        ->toHaveKeys(['id','name','surname','phone','email','created_at','updated_at','deleted_at']);
    });

    test('try to get a customer without system logged in', function() {
        $response = $this->getJson(route('customers.index'));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test('when there are no customers', function() {
        $response = $this->actingAs($this->systemUser)->getJson(route('customers.index'));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toHaveCount(0);
    });
});

