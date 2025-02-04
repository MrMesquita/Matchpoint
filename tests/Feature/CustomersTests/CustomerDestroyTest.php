<?php

use App\Models\User;
use App\Models\Customer;

beforeEach(function () {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->customer = Customer::factory()->count(1)->create()->first();
});

describe('destroy a customer', function () {
    test("can be destroy a customer", function () {
        $response = $this->actingAs($this->systemUser)->deleteJson(route('customers.destroy', ['customer' => $this->customer->id]));
    
        expect($response->getStatusCode())->toBe(204);
        expect(Customer::where('id', $this->customer->id)->exists())->toBeFalse();
    });

    test('try to destroy a customer without system logged in', function() {
        $response = $this->deleteJson(route('customers.destroy', ['customer' => $this->customer->id]));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("try to destroy a customer that doesn't exist", function() {
        $response = $this->actingAs($this->systemUser)->deleteJson(route('customers.destroy', ['customer' => 0]));

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});
