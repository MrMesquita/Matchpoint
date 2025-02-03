<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Arena;

beforeEach(function () {
    $this->adminUser = Admin::factory()->count(1)->create()->first();
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->arena = Arena::factory()->count(1)->create(['admin_id' => $this->adminUser->id])->first();

    $this->updatedArenaData = [
        "name" => "Arena 1",
        "street" => "Rua lagoa",
        "number" => "44",
        "neighborhood" => "Bairro",
        "city" => "Vitoria",
        "state" => "PE",
        "zip_code" => "55606900",
        "admin_id" => $this->adminUser->id
    ];
});

describe('update an arena', function () {
    test("admin can be update an arena", function () {
        $response = $this->actingAs($this->adminUser)->putJson(route('arenas.update', ['arena' => $this->arena->id]), $this->updatedArenaData);

        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray([
            "name" => $this->updatedArenaData['name'],
            "street" => $this->updatedArenaData['street'],
            "number" => $this->updatedArenaData['number'],
            "neighborhood" => $this->updatedArenaData['neighborhood'],
            "city" => $this->updatedArenaData['city'],
            "state" => $this->updatedArenaData['state'],
            "zip_code" => $this->updatedArenaData['zip_code'],
            "admin_id" => $this->updatedArenaData['admin_id']
        ]);
    });

    test("admin tries to update an arena that doesn't belong to him", function () {
        $newArena = Arena::factory()->count(1)->create()->first();
        $response = $this->actingAs($this->adminUser)->putJson(route('arenas.update', ['arena' => $newArena->id]), $this->updatedArenaData);

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("system can be update an arena", function () {
        $admin = Admin::factory()->count(1)->create()->first();
        $this->updatedArenaData["admin_id"] = $admin->id;

        $response = $this->actingAs($this->systemUser)->putJson(route('arenas.update', ['arena' => $this->arena->id]), $this->updatedArenaData);

        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray([
            "name" => $this->updatedArenaData['name'],
            "street" => $this->updatedArenaData['street'],
            "number" => $this->updatedArenaData['number'],
            "neighborhood" => $this->updatedArenaData['neighborhood'],
            "city" => $this->updatedArenaData['city'],
            "state" => $this->updatedArenaData['state'],
            "zip_code" => $this->updatedArenaData['zip_code'],
            "admin_id" => $admin->id
        ]);
    });

    test('try to update an arena without logged in', function() {
        $response = $this->putJson(route('arenas.update', ['arena' => $this->arena->id]), $this->updatedArenaData);

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("system tries to update an arena with an admin that doesn't exist", function() {
        $this->updatedArenaData['admin_id'] = 0;
        $response = $this->actingAs($this->systemUser)->putJson(route('arenas.update', ['arena' => $this->arena->id]), $this->updatedArenaData);
        
        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to store an arena with invalid data", function() {
        $this->updatedArenaData = [];
        $response = $this->actingAs($this->adminUser)->putJson(route('arenas.update', ['arena' => $this->arena->id]), $this->updatedArenaData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });
});