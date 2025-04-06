<?php

use App\Models\Admin;
use App\Models\User;

beforeEach(function() {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
});

describe('fetch all admins', function() {
    test('can fetch all admins', function () {
        Admin::factory()->count(3)->create();
        $response = $this->actingAs($this->systemUser)->getJson(route('admins.index'));

        expect($response->status())->toBe(200)
            ->and($response->json())->toHaveKeys(['success', 'results'])
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('results'))->toHaveCount(3)
            ->and($response->json('results'))
            ->each()
            ->toHaveKeys(['id', 'name', 'surname', 'phone', 'email', 'type', 'created_at', 'updated_at', 'deleted_at']);

    });

    test('try to get an admin without system logged in', function() {
        $response = $this->getJson(route('admins.index'));

        expect($response->status())->toBe(401)
            ->and($response->json())->toHaveKeys(['success', 'message'])
            ->and($response->json('success'))->toBeFalse()
            ->and($response->json('message'))->toBeString();
    });

    test('when there are no admins', function() {
        $response = $this->actingAs($this->systemUser)->getJson(route('admins.index'));

        expect($response->status())->toBe(200)
            ->and($response->json())->toHaveKeys(['success', 'results'])
            ->and($response->json('success'))->toBeTrue()
            ->and($response->json('results'))->toHaveCount(0);
    });
});

