<?php

use App\Models\User;
use App\Models\Admin;

beforeEach(function () {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->admin = Admin::factory()->count(1)->create()->first();
});

describe('destroy an admin', function () {
    test("can be destroy an admin", function () {
        $response = $this->actingAs($this->systemUser)->deleteJson(route('admins.destroy', ['admin' => $this->admin->id]));
    
        expect($response->getStatusCode())->toBe(204);
        expect(Admin::where('id', $this->admin->id)->exists())->toBeFalse();
    });

    test('try to destroy an admin without system logged in', function() {
        $response = $this->deleteJson(route('admins.destroy', ['admin' => $this->admin->id]));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("try to destroy an admin that doesn't exist", function() {
        $response = $this->actingAs($this->systemUser)->deleteJson(route('admins.destroy', ['admin' => 0]));

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});
