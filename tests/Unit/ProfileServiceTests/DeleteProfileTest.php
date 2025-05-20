<?php

use App\Models\User;
use App\Services\ProfileService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

test("user can be deleted", function () {
    $user = mock(User::class);
    $user->shouldReceive('getAttribute')
        ->once()->with('id')
        ->andReturn(1);

    $user->shouldReceive('delete')
        ->once()->andReturn(true);

    Auth::shouldReceive('user')->andReturn($user);

    $userService = mock(UserService::class);
    $userService->shouldReceive('getUserById')->once()->andReturn($user);

    $profileService = new ProfileService($userService);
    $profileService->deleteProfile();

});

test('try delete profile without login', function () {
    $userService = mock(UserService::class);
    $userService->shouldReceive('getUserById')
        ->once()->andThrow(ModelNotFoundException::class);

    $profileService = new ProfileService($userService);

    $profileService->deleteProfile();
})->throws(ModelNotFoundException::class);
