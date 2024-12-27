<?php

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

describe("customer logout test", function() {
    test("connected customer tries to log out", function() {
        $customer = Customer::create([
            "name" => "Marcelo",
            "surname" => "Mesquita",
            "phone" => "3435333212",
            "email" => "customer@test.com",
            "password" => Hash::make("customer123")
        ]);

        $response = $this->actingAs($customer)->postJson(route('auth.logout'));
        
        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('message'))->toBeString();
    });

    test("disconnected customer tries to log out", function() {
        $response = $this->postJson(route('auth.logout'));
        
        expect($response->getStatusCode())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});