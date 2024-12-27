<?php

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

describe("admin logout test", function() {
    test("connected admin tries to log out", function() {
        $admin =  Admin::create([
            "name" => "Marcelo",
            "surname" => "Mesquita",
            "phone" => "3435333212",
            "email" => "admin@test.com",
            "password" => Hash::make("admin123")
        ]);

        $response = $this->actingAs($admin)->postJson(route('auth.logout'));
        
        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('message'))->toBeString();
    });

    test("disconnected admin tries to log out", function() {
        $response = $this->postJson(route('auth.logout'));
        
        expect($response->getStatusCode())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});