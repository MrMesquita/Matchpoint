<?php

use App\Models\Customer;
use App\Models\User;

beforeEach(function () {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
});

describe('store a customer', function () {
    test("can be store a customer", function () {
        $customerData = [
            "name" => "Marcelo",
            "surname" => "Mesquita",
            "phone" => "3435333212",
            "email" => "customer@test.com",
            "password" => "customer123"
        ];

        $response = $this->actingAs($this->systemUser)->postJson(route('customers.store'), $customerData);

        expect($response->getStatusCode())->toBe(201);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray([
            "name" => "Marcelo",
            "surname" => "Mesquita",
            "phone" => "3435333212",
            "email" => "customer@test.com",
        ]);
        
        expect(Customer::where('email', 'customer@test.com')->exists())->toBeTrue();
    });

    test('try to store a customer without system logged in', function() {
        $response = $this->getJson(route('customers.store'));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to store a customer with invalid data", function() {
        $customerData = [
            "name" => "Marcelo",
            "surname" => "",
            "phone" => "",
            "email" => "marcelo@gmail.cc",
            "password" => "123456"
        ];

        $response = $this->actingAs($this->systemUser)->postJson(route('customers.store'), $customerData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });
});
