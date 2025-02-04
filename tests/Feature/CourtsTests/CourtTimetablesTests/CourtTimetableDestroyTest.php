<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Arena;
use App\Models\Court;
use App\Models\CourtTimetable;

beforeEach(function () {
    $this->courtTimetableModel = new CourtTimetable();

    $this->adminUser = Admin::factory()->create();
    $this->systemUser = User::where('email', env('SYSTEM_EMAIL'))->first();
    $this->arena = Arena::factory()->create(['admin_id' => $this->adminUser->id]);
    $this->court = Court::factory()->create(['arena_id' => $this->arena->id]);
    $this->courtTimetable = CourtTimetable::factory()->create(['court_id' => $this->court->id]);
});

describe('destroy a court timetable', function () {
    test("admin can destroy a timetable", function () {
        $response = $this->actingAs($this->adminUser)
            ->deleteJson(
                route('timetables.destroy', [
                    'court' => $this->court->id,
                    'timetable' => $this->courtTimetable->id
                ])
            );
    
        expect($response->getStatusCode())->toBe(204);
        expect(CourtTimetable::where('id', $this->courtTimetable->id)->exists())->toBeFalse();
    });

    test("system can destroy a court", function () {
        $newCourtTimetable = CourtTimetable::factory()->create(['court_id' => $this->court->id]);
        $response = $this->actingAs($this->systemUser)
            ->deleteJson(
                route('timetables.destroy', [
                    'court' => $this->court->id,
                    'timetable' => $newCourtTimetable->id
                ])
            );

        expect($response->getStatusCode())->toBe(204);
        expect(CourtTimetable::where('id', $newCourtTimetable->id)->exists())->toBeFalse();
    });

    test('try to destroy a court timetable without logged in', function() {
        $response = $this->deleteJson(
                route('timetables.destroy', [
                    'court' => $this->court->id,
                    'timetable' => $this->courtTimetable
                ])
            );

        expect($response->status())->toBe(401);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });

    test("admin try to destroy a court timetable that doesn't exist or that doesn't belong to him", function() {
        $newCourtTimetable = CourtTimetable::factory()->create();
        $response = $this->actingAs($this->adminUser)
            ->deleteJson(
                route('timetables.destroy', [
                    'court' => $newCourtTimetable->court->id,
                    'timetable' => $newCourtTimetable->id
                ])
            );

        expect($response->status())->toBe(404);
        expect($response->json())->toHaveKeys(['success', 'message']);
        expect($response->json('success'))->toBeFalse();
        expect($response->json('message'))->toBeString();
    });
});
