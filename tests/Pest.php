<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function checkSuccessCase($response, $count = null)
{
    expect($response->status())->toBe(200);
    expect($response->json())->toHaveKeys(['success', 'results']);
    expect($response->json('success'))->toBeTrue();

    if ($count) {
        expect($response->json('results'))->toHaveCount($count);
    }
}

function checkCreatedCase($response)
{
    expect($response->getStatusCode())->toBe(201);
    expect($response->json())->toHaveKeys(['success', 'results']);
    expect($response->json('success'))->toBeTrue();
    expect($response->json('results')[0])->toHaveKeys(['id', 'created_at', 'updated_at']);
}

function checkValidationErrorCase($response)
{
    expect($response->status())->toBe(400);
    expect($response->json())->toHaveKeys(['success', 'message', 'errors']);
    expect($response->json('success'))->toBeFalse();
    expect($response->json('message'))->toBeString();
}

function checkNotFoundCase($response)
{
    expect($response->status())->toBe(404);
    expect($response->json())->toHaveKeys(['success', 'message']);
    expect($response->json('success'))->toBeFalse();
    expect($response->json('message'))->toBeString();
}

function checkReservationsResults($reservations)
{
    foreach ($reservations as $reservation) {
        expect($reservation)->toHaveKeys([
            'id',
            'customer_id',
            'court_id',
            'court_timetable_id',
            'status',
            'customer',
            'court',
            'court_timetable'
        ]);

        expect($reservation['customer'])->toHaveKeys(['id', 'name', 'surname', 'email']);
        expect($reservation['court'])->toHaveKeys(['id', 'name', 'capacity', 'arena_id']);
        expect($reservation['court_timetable'])->toHaveKeys(['id', 'day_of_week', 'start_time', 'end_time', 'status']);
    }
}
