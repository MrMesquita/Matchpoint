<?php

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Admin::create([
        "name" => "Marcelo",
        "surname" => "Mesquita",
        "phone" => "3435333212",
        "email" => "admin@test.com",
        "password" => Hash::make("admin123")
    ]);
});

describe("auth attemps admin", function() {
    test('admin can log in', function() {
        $loginData = [
            'email' => $this->admin->email,
            'password' => 'admin123'
        ];
    
        $response = $this->postJson(route('auth.login'), $loginData);
    
        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results.token'))->toBeString();
    });

    test('admin send invalid fields', function() {
        $loginData = [
            'email' => $this->admin->email,
            'password' => ''
        ];
    
        $response = $this->postJson(route('auth.login'), $loginData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'message', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });

    test("admin email or password doesn't matches", function() {
        $loginData = [
            'email' => $this->admin->email,
            'password' => 'admin'
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