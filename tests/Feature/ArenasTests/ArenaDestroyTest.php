<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Arena;

beforeEach(function () {
    $this->adminUser = Admin::factory()->count(1)->create()->first();
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->arena = Arena::factory()->count(1)->create(['admin_id' => $this->adminUser->id])->first();
});

describe('destroy an arena', function () {
    test("admin can be destroy an arena", function () {
        $response = $this->actingAs($this->adminUser)
            ->deleteJson(
                route('arenas.destroy', ['arena' => $this->arena->id])
            );
    
        expect($response->getStatusCode())->toBe(204);
        expect(Arena::where('id', $this->arena->id)->exists())->toBeFalse();
    });

    test("system can be destroy an arena", function () {
        $newArena = Arena::factory()->count(1)->create()->first();
        $response = $this->actingAs($this->systemUser)
            ->deleteJson(
                route('arenas.destroy', ['arena' => $newArena->id])
            );
    
        expect($response->getStatusCode())->toBe(204);
        expect(Arena::where('id', $newArena->id)->exists())->toBeFalse();
    });

    test('try to destroy an arena without logged in', function() {
        $response = $this
            ->deleteJson(
                route('arenas.destroy', ['arena' => $this->arena->id])
            );

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("admin try to destroy an arena that doesn't exist or that doesn't belong to him", function() {
        $response = $this->actingAs($this->adminUser)
            ->deleteJson(
                route('arenas.destroy', ['arena' => 0])
            );

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});
