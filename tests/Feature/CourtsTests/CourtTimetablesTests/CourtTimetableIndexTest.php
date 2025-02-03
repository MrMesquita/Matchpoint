<?php

use App\Models\Admin;
use App\Models\Arena;
use App\Models\Court;
use App\Models\CourtTimetable;

beforeEach(function() {
    $this->adminUser = Admin::factory()->create();
    $this->arena = Arena::factory()->create(['admin_id' => $this->adminUser->id]);
    $this->court = Court::factory()->create(['arena_id' => $this->arena->id]);
});

describe('fetch court timetables', function() {
    test('can fetch court timetables by courtId', function () {
        CourtTimetable::factory()->count(3)->create(['court_id' => $this->court->id]);
        $response = $this->actingAs($this->adminUser)->getJson(route('timetables.index', ['court' => $this->court->id]));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toHaveCount(3);

        expect($response->json('results'))
        ->each()
        ->toHaveKeys(['id','court_id','day_of_week','start_time','end_time','status']);
    });

    test('try to get timetables without logged in', function() {
        $response = $this->getJson(route('timetables.index', ['court' => $this->court->id]));

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test('when there are no timetables', function() {
        $response = $this->actingAs($this->adminUser)->getJson(route('timetables.index', ['court' => $this->court->id]));

        expect($response->status())->toBe(200);
        expect($response->json())->toHaveKeys(['success', 'results']);
        expect($response->json('success'))->toBeTrue();
        expect($response->json('results'))->toHaveCount(0);
    });
});