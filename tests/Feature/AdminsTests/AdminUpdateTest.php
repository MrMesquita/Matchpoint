<?php

use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->admin = Admin::factory()->count(1)->create()->first();

    $this->updatedAdminData = [
        "name" => "Marcelo",
        "surname" => "Mesquita",
        "phone" => "3435333212",
        "email" => "admin@test.com",
        "password" => "admin123"
    ];
});

describe('update an admin', function () {
    test("can be update an admin", function () {
        $response = $this->actingAs($this->systemUser)->putJson(route('admins.update', ['admin' => $this->admin->id]), $this->updatedAdminData);

        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toMatchArray([
            "name" => $this->updatedAdminData['name'],
            "surname" => $this->updatedAdminData['surname'],
            "phone" => $this->updatedAdminData['phone'],
            "email" => $this->updatedAdminData['email']
        ]);
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
    });

    test('try to update an admin without system logged in', function() {
        $response = $this->getJson(route('admins.update', ['admin' => $this->admin->id]), $this->updatedAdminData);

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("try to update an admin that doesn't exist", function() {
        $response = $this->actingAs($this->systemUser)->getJson(route('admins.update', ['admin' => 0]), $this->updatedAdminData);

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to update an admin with invalid data", function() {
        $this->updatedAdminData = [
            "name" => "Marcelo",
            "surname" => "",
            "phone" => "",
            "email" => "marcelo@gmail.cc",
            "password" => "123456"
        ];

        $response = $this->actingAs($this->systemUser)->putJson(route('admins.update', ['admin' => $this->admin->id]), $this->updatedAdminData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });
});
