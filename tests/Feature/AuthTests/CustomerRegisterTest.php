<?php

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

describe("Auth register", function() {
    test("Customer tries to register", function() {
        $registerData = [
            "name" => "Marcelo",
            "surname" => "Mesquita",
            "phone" => "813398145",
            "email" => "marcelo@gmail.cc",
            "password" => "123456"
        ];

        $response = $this->postJson(route('auth.register'), $registerData);

        expect($response->getStatusCode())->toBe(201);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('message'))->toBeString();
        expect($response->json('results.token'))->toBeString();
    });

    test("Customer tries to register with invalid data", function() {
        $registerData = [
            "name" => "Marcelo",
            "surname" => "",
            "phone" => "",
            "email" => "marcelo@gmail.cc",
            "password" => "123456"
        ];

        $response = $this->postJson(route('auth.register'), $registerData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });
});