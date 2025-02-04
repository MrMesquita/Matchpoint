<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Arena;
use App\Models\Court;

beforeEach(function () {
    $this->adminUser = Admin::factory()->create();
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $arena = Arena::factory()->create(['admin_id' => $this->adminUser->id]);

    $this->court = Court::factory()->create(['arena_id' => $arena->id]);
    $this->updatedCourtData = [
        "name" => "Court 5",
        "capacity" => 10,
        "arena_id" => $arena->id
    ];
});

describe('update a court', function () {
    test("admin can update a court", function () {
        $response = $this->actingAs($this->adminUser)
            ->putJson(
                route('courts.update', ['court' => $this->court->id]),
                $this->updatedCourtData
            );

        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray($this->updatedCourtData);
    });

    test("admin tries to update a court that doesn't belong to him", function () {
        $newArena = Arena::factory()->create();
        $response = $this->actingAs($this->adminUser)
            ->putJson(
                route('courts.update', ['court' => $newArena->id]),
                $this->updatedCourtData
            );

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("system can update an arena", function () {
        $newArena = Arena::factory()->create();
        $this->updatedCourtData["arena_id"] = $newArena->id;

        $response = $this->actingAs($this->systemUser)
            ->putJson(
                route('courts.update', ['court' => $this->court->id]),
                $this->updatedCourtData
            );

        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray($this->updatedCourtData);
    });

    test('try to update an arena without logged in', function() {
        $response = $this->putJson(
            route('courts.update', ['court' => $this->court->id]),
            $this->updatedCourtData
        );

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("system tries to update a court with an arena that doesn't exist", function() {
        $this->updatedCourtData['arena_id'] = 0;

        $response = $this->actingAs($this->systemUser)
            ->putJson(
                route('courts.update', ['court' => $this->court->id]),
                $this->updatedCourtData
            );
        
        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to store an arena with invalid data", function() {
        $this->updatedCourtData = [];

        $response = $this->actingAs($this->adminUser)
            ->putJson(
                route('courts.update', ['court' => $this->court->id]),
                $this->updatedCourtData
            );

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });
});