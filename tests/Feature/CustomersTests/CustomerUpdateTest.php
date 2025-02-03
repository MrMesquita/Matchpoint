<?php

use App\Models\User;
use App\Models\Customer;

beforeEach(function () {
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->customer = Customer::factory()->count(1)->create()->first();

    $this->updatedCustomerData = [
        "name" => "Marcelo",
        "surname" => "Mesquita",
        "phone" => "3435333212",
        "email" => "customer@test.com",
        "password" => "customer123"
    ];
});

describe('update a customer', function () {
    test("can be update a customer", function () {
        $response = $this->actingAs($this->systemUser)->putJson(route('customers.update', ['customer' => $this->customer->id]), $this->updatedCustomerData);

        expect($response->getStatusCode())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toMatchArray([
            "name" => $this->updatedCustomerData['name'],
            "surname" => $this->updatedCustomerData['surname'],
            "phone" => $this->updatedCustomerData['phone'],
            "email" => $this->updatedCustomerData['email']
        ]);
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
    });

    test('try to update a customer without system logged in', function () {
        $response = $this->getJson(route('customers.update', ['customer' => $this->customer->id]), $this->updatedCustomerData);

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("try to update a customer that doesn't exist", function () {
        $response = $this->actingAs($this->systemUser)->getJson(route('customers.update', ['customer' => 0]), $this->updatedCustomerData);

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("tries to update a customer with invalid data", function () {
        $this->updatedCustomerData = [
            "name" => "Marcelo",
            "surname" => "",
            "phone" => "",
            "email" => "marcelo@gmail.cc",
            "password" => "123456"
        ];

        $response = $this->actingAs($this->systemUser)->putJson(route('customers.update', ['customer' => $this->customer->id]), $this->updatedCustomerData);

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });
});
