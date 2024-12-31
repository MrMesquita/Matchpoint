<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Arena;
use App\Models\Court;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminUser = Admin::factory()->create()->first();
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->arena = Arena::factory()->create(['admin_id' => $this->adminUser->id])->first();

    $this->courtData = [
        "name" => "Court 5",
        "capacity" => 10,
        "arena_id" => $this->arena->id
    ];
});

describe('store a court', function () {
    test("admin can be store a court", function () {
        $response = $this->actingAs($this->adminUser)->postJson(route('courts.store'), $this->courtData);

        expect($response->getStatusCode())->toBe(201);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray($this->courtData);
        
        expect(Court::where('name', $this->courtData['name'])
            ->where('arena_id', $this->courtData['arena_id'])
            ->exists()
        )->toBeTrue();
    });

    test("admin can't be store a court to a arena that doesn't belong to him", function () {
        $arena = Arena::factory()->create();
        $this->courtData['arena_id'] = $arena->id;

        $response = $this->actingAs($this->adminUser)->postJson(route('courts.store'), $this->courtData);

        expect($response->getStatusCode())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        
        expect(Court::where('name', $this->courtData['name'])
            ->where('arena_id', $this->courtData['arena_id'])
            ->exists()
        )->toBeFalse();
    });

    test("system can be store a court", function () {
        $response = $this->actingAs($this->systemUser)->postJson(route('courts.store'), $this->courtData);

        expect($response->getStatusCode())->toBe(201);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray($this->courtData);

        expect(Court::where('name', $this->courtData['name'])
            ->where('arena_id', $this->courtData['arena_id'])
            ->exists()
        )->toBeTrue();
    });

    test('try to store a court without logged in', function() {
        $response = $this->postJson(route('courts.store'), $this->courtData);

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test('customer try to store a court', function() {
        $customer = Customer::factory()->create()->first();
        $response = $this->actingAs($customer)->postJson(route('courts.store'), $this->courtData);

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("system tries to store a court with arena that doesn't exist", function() {
        $this->courtData['arena_id'] = 999999;
        $response = $this->actingAs($this->systemUser)->postJson(route('courts.store', $this->courtData));
        
        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to store a court with invalid data", function() {
        $this->courtData = [];
        $response = $this->actingAs($this->systemUser)->postJson(route('courts.store'), $this->courtData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });

    test("tries to register a court with a name that already exists for the arena", function() {
        Court::factory()->create($this->courtData);
        $response = $this->actingAs($this->adminUser)->postJson(route('courts.store'), $this->courtData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });
});
