<?php

use App\Models\User;
use App\Models\Admin;

beforeEach(function () {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
});

describe('store an admin', function () {
    test("can be store an admin", function () {
        $adminData = [
            "name" => "Marcelo",
            "surname" => "Mesquita",
            "phone" => "3435333212",
            "email" => "admin@test.com",
            "password" => "admin123"
        ];

        $response = $this->actingAs($this->systemUser)->postJson(route('admins.store'), $adminData);

        expect($response->getStatusCode())->toBe(201);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray([
            "name" => "Marcelo",
            "surname" => "Mesquita",
            "phone" => "3435333212",
            "email" => "admin@test.com",
            "type" => "admin",
        ]);
        
        expect(Admin::where('email', 'admin@test.com')->exists())->toBeTrue();
    });

    test('try to store an admin without system logged in', function() {
        $response = $this->getJson(route('admins.store'));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to store an admin with invalid data", function() {
        $adminData = [
            "name" => "Marcelo",
            "surname" => "",
            "phone" => "",
            "email" => "marcelo@gmail.cc",
            "password" => "123456"
        ];

        $response = $this->actingAs($this->systemUser)->postJson(route('admins.store'), $adminData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });
});
