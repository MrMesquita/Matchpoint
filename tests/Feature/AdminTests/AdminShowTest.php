<?php

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->admin = Admin::factory()->count(1)->create()->first();
});

describe('fetch a admin by id', function() {
    test('is it possible to get an admin', function () {        
        $response = $this->actingAs($this->systemUser)->getJson(route('admins.show', ['admin' => $this->admin->id]));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toBeGreaterThanOrEqual(1);
        expect($response->json('results.0'))
        ->toMatchArray([
            'id' => $this->admin->id,
            'name' => $this->admin->name,
            'surname' => $this->admin->surname,
            'phone' => $this->admin->phone,
            'email' => $this->admin->email,
            'type' => $this->admin->type
        ]);
    });

    test('try to get an admin without system logged in', function() {
        $response = $this->getJson(route('admins.show', ['admin' => $this->admin->id]));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to get an administrator that doesn't exist", function() {
        $response = $this->actingAs($this->systemUser)->getJson(route('admins.show', ['admin' => 0]));

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});