<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Arena;
use App\Models\Court;
use App\Models\CourtTimetable;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminUser = Admin::factory()->create();
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->arena = Arena::factory()->create(['admin_id' => $this->adminUser->id]);
    $this->court = Court::factory()->create(['arena_id' => $this->arena->id]);
    $this->courtTimetable = new CourtTimetable();

    $this->timetableData = [
        "day_of_week" => "1",
        "start_time" => "05:00",
        "end_time" => "06:00",
        "status" => "available"
    ];
});

describe('store a court timetable', function () {
    test("admin can store a court timetable", function () {
        $response = $this->actingAs($this->adminUser)
            ->postJson(
                route('timetables.store', ['court' => $this->court->id]),
                $this->timetableData
            );

        expect($response->getStatusCode())->toBe(201);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray($this->timetableData);
        
        expect($this->courtTimetable->existsConflictingTimetable(
            $this->court->id,
            $this->timetableData['day_of_week'],
            $this->timetableData['end_time'],
            $this->timetableData['start_time']
        ))->toBeTrue();
    });

    test("admin can't store a timetable to a court that doesn't belong to him", function () {
        $newCourt = Court::factory()->create();
        $response = $this->actingAs($this->adminUser)
            ->postJson(
                route('timetables.store', ['court' => $newCourt->id]), 
                $this->timetableData
            );

        expect($response->getStatusCode())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        
        expect($this->courtTimetable->existsConflictingTimetable(
            $newCourt->id,
            $this->timetableData['day_of_week'],
            $this->timetableData['end_time'],
            $this->timetableData['start_time']
        ))->toBeFalse();
    });

    test("system can store a court timetable", function () {
        $response = $this->actingAs($this->systemUser)
            ->postJson(
                route('timetables.store', ['court' => $this->court->id]),
                $this->timetableData
            );

        expect($response->getStatusCode())->toBe(201);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
        expect($response->json('results')[0])->toMatchArray($this->timetableData);

        expect($this->courtTimetable->existsConflictingTimetable(
            $this->court->id,
            $this->timetableData['day_of_week'],
            $this->timetableData['end_time'],
            $this->timetableData['start_time']
        ))->toBeTrue();
    });

    test('try to store a court timetable without logged in', function() {
        $response = $this->postJson(
            route('timetables.store', ['court' => $this->court->id]),
            $this->timetableData
        );

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();

        expect($this->courtTimetable->existsConflictingTimetable(
            $this->court->id,
            $this->timetableData['day_of_week'],
            $this->timetableData['end_time'],
            $this->timetableData['start_time']
        ))->toBeFalse();
    });

    test('customer try to store a court timetable', function() {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer)
            ->postJson(
                route('timetables.store', ['court' => $this->court->id]),
                $this->timetableData
            );

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();

        expect($this->courtTimetable->existsConflictingTimetable(
            $this->court->id,
            $this->timetableData['day_of_week'],
            $this->timetableData['end_time'],
            $this->timetableData['start_time']
        ))->toBeFalse();
    });

    test("system tries to store a timetable with court that doesn't exist", function() {
        $response = $this->actingAs($this->systemUser)
            ->postJson(
                route('timetables.store', ['court' => 0]),
                $this->timetableData
            );
        
        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();

        expect($this->courtTimetable->existsConflictingTimetable(
            $this->court->id,
            $this->timetableData['day_of_week'],
            $this->timetableData['end_time'],
            $this->timetableData['start_time']
        ))->toBeFalse();
    });

    test("tries to store a court with invalid data", function() {
        $this->timetableData = [];

        $response = $this->actingAs($this->adminUser)
            ->postJson(
                route('timetables.store', ['court' => $this->court->id]),
                $this->timetableData
            );

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();
    });

    test("tries to register a court timetable whose start time conflicts with an existing one", function() {
        CourtTimetable::factory()->create(array_merge($this->timetableData, ['court_id' => $this->court->id]));
        $this->timetableData['start_time'] = '05:30';
        $this->timetableData['end_time'] = '06:30';

        $response = $this->actingAs($this->adminUser)
            ->postJson(
                route('timetables.store', ['court' => $this->court->id]),
                $this->timetableData
            );

        expect($response->getStatusCode())->toBe(400);
        expect($response->json())->toHaveKeys(['success', 'errors']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
        expect($response->json('errors'))->toBeArray();

        expect($this->courtTimetable->existsConflictingTimetable(
            $this->court->id,
            $this->timetableData['day_of_week'],
            $this->timetableData['end_time'],
            $this->timetableData['start_time']
        ))->toBeTrue();
    });
});
