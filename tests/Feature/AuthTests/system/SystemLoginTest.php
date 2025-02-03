<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe("auth attemps system", function() {
    test('system can log in', function() {
        $loginData = [
            'email' => env('SYSTEM_EMAIL'),
            'password' => env('SYSTEM_PASSWORD')
        ];
    
        $response = $this->postJson(route('auth.login'), $loginData);
    
        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results.token'))->toBeString();
    });

    test('system send invalid fields', function() {
        $loginData = [
            'email' => env('SYSTEM_EMAIL'),
            'password' => ''
        ];
    
        $response = $this->postJson(route('auth.login'), $loginData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'message', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });

    test("system email or password doesn't matches", function() {
        $loginData = [
            'email' => env('SYSTEM_EMAIL'),
            'password' => 'wrongPassword'
        ];
    
        $response = $this->postJson(route('auth.login'), $loginData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'message', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
        expect($response->json('errors.login.0'))->toBeString();
    });
});