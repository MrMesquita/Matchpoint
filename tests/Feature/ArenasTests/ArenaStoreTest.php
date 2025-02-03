<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Arena;

beforeEach(function () {
    $this->adminUser = Admin::factory()->count(1)->create()->first();
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();

    $this->arenaData = [
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

describe('store an arena', function () {
    test("admin can be store an arena", function () {
        $response = $this->actingAs($this->adminUser)->postJson(route('arenas.store'), $this->arenaData);

        expect($response->getStatusCode())->toBe(201);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray([
            "name" => $this->arenaData['name'],
            "street" => $this->arenaData['street'],
            "number" => $this->arenaData['number'],
            "neighborhood" => $this->arenaData['neighborhood'],
            "city" => $this->arenaData['city'],
            "state" => $this->arenaData['state'],
            "zip_code" => $this->arenaData['zip_code'],
            "admin_id" => $this->adminUser->id
        ]);
        
        expect(Arena::where('name', $this->arenaData['name'])
            ->where('admin_id', $this->adminUser->id)
            ->exists()
        )->toBeTrue();
    });

    test("system can be store an arena", function () {
        $admin = Admin::factory()->count(1)->create()->first();
        $this->arenaData["admin_id"] = $admin->id;

        $response = $this->actingAs($this->systemUser)->postJson(route('arenas.store'), $this->arenaData);

        expect($response->getStatusCode())->toBe(201);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray([
            "name" => $this->arenaData['name'],
            "street" => $this->arenaData['street'],
            "number" => $this->arenaData['number'],
            "neighborhood" => $this->arenaData['neighborhood'],
            "city" => $this->arenaData['city'],
            "state" => $this->arenaData['state'],
            "zip_code" => $this->arenaData['zip_code'],
            "admin_id" => $admin->id
        ]);

        expect(Arena::where('name', 'Arena 1')->where('admin_id', $admin->id)->exists())->toBeTrue();
    });

    test("admin tries to register an arena with a different id", function () {
        $admin = Admin::factory()->count(1)->create()->first();
        $this->arenaData['admin_id'] = $admin->id;

        $response = $this->actingAs($this->adminUser)->postJson(route('arenas.store'), $this->arenaData);

        expect($response->getStatusCode())->toBe(201);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray([
            "name" => $this->arenaData['name'],
            "street" => $this->arenaData['street'],
            "number" => $this->arenaData['number'],
            "neighborhood" => $this->arenaData['neighborhood'],
            "city" => $this->arenaData['city'],
            "state" => $this->arenaData['state'],
            "zip_code" => $this->arenaData['zip_code'],
            "admin_id" => $this->adminUser->id
        ]);
        
        expect(Arena::where('name', $this->arenaData['name'])
            ->where('admin_id', $this->adminUser->id)
            ->exists()
        )->toBeTrue();
    });

    test('try to store an arena without logged in', function() {
        $response = $this->getJson(route('arenas.store'));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("system tries to store an arena with an admin that doesn't exist", function() {
        $this->arenaData['admin_id'] = 0;
        $response = $this->actingAs($this->systemUser)->postJson(route('arenas.store', $this->arenaData));
        
        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to store an arena with invalid data", function() {
        $this->arenaData = [];
        $response = $this->actingAs($this->systemUser)->postJson(route('arenas.store'), $this->arenaData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });
});
