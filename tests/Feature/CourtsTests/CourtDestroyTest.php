<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Arena;
use App\Models\Court;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminUser = Admin::factory()->create();
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->arena = Arena::factory()->create(['admin_id' => $this->adminUser->id]);
    $this->court = Court::factory()->create(['arena_id' => $this->arena->id]);
});

describe('destroy a court', function () {
    test("admin can destroy a court", function () {
        $response = $this->actingAs($this->adminUser)
            ->deleteJson(
                route('courts.destroy', ['court' => $this->court->id])
            );
    
        expect($response->getStatusCode())->toBe(204);
        expect(Court::where('id', $this->court->id)->exists())->toBeFalse();
    });

    test("system can destroy a court", function () {
        $newCourt = Court::factory()->create();
        $response = $this->actingAs($this->systemUser)
            ->deleteJson(
                route('courts.destroy', ['court' => $newCourt->id])
            );
    
        expect($response->getStatusCode())->toBe(204);
        expect(Court::where('id', $newCourt->id)->exists())->toBeFalse();
    });

    test('try to destroy a court without logged in', function() {
        $response = $this->deleteJson(
            route('courts.destroy', ['court' => $this->court->id])
        );

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("admin try to destroy a court that doesn't exist or that doesn't belong to him", function() {
        $newCourt = Court::factory()->create();
        $response = $this->actingAs($this->adminUser)
            ->deleteJson(
                route('courts.destroy', 
                ['court' => $newCourt->id])
            );

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});
