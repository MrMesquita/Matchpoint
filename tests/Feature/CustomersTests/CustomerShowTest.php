<?php

use App\Models\Customer;
use App\Models\User;

beforeEach(function() {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->customer = Customer::factory()->count(1)->create()->first();
});

describe('fetch a customer by id', function() {
    test('is it possible to get a customer', function () {        
        $response = $this->actingAs($this->systemUser)->getJson(route('customers.show', ['customer' => $this->customer->id]));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toBeGreaterThanOrEqual(1);
        expect($response->json('results.0'))
        ->toMatchArray([
            'id' => $this->customer->id,
            'name' => $this->customer->name,
            'surname' => $this->customer->surname,
            'phone' => $this->customer->phone,
            'email' => $this->customer->email,
        ]);
    });

    test('try to get a customer without system logged in', function() {
        $response = $this->getJson(route('customers.show', ['customer' => $this->customer->id]));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to get a customer that doesn't exist", function() {
        $response = $this->actingAs($this->systemUser)->getJson(route('customers.show', ['customer' => 0]));

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});