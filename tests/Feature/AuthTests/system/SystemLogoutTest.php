<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe("auth logout system", function() {
    test("connected system tries to log out", function() {
        $systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
        $response = $this->actingAs($systemUser)->postJson(route('auth.logout'));
        
        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('message'))->toBeString();
    });

    test("disconnected system tries to log out", function() {
        $response = $this->postJson(route('auth.logout'));
        
        expect($response->getStatusCode())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});