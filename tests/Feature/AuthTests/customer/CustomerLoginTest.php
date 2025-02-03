<?php

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->customer = Customer::create([
        "name" => "Marcelo",
        "surname" => "Mesquita",
        "phone" => "3435333212",
        "email" => "customer@test.com",
        "password" => Hash::make("customer123")
    ]);
});

describe("auth attemps customer", function() {
    test('customer can log in', function() {
        $loginData = [
            'email' => $this->customer->email,
            'password' => 'customer123'
        ];
    
        $response = $this->postJson(route('auth.login'), $loginData);
    
        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results.token'))->toBeString();
    });

    test('customer send invalid fields', function() {
        $loginData = [
            'email' => $this->customer->email,
            'password' => ''
        ];
    
        $response = $this->postJson(route('auth.login'), $loginData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'message', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });

    test("customer email or password doesn't matches", function() {
        $loginData = [
            'email' => $this->customer->email,
            'password' => 'customer'
        ];
    
        $response = $this->postJson(route('auth.login'), $loginData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'message', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
        expect($response->json('errors.login.0'))->toBeString();
    });
});