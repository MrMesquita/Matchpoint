<?php

use App\Services\AuthService;
use App\Services\CustomerService;
use App\Services\UserService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Mail\CustomResetPasswordMail;
use Illuminate\Validation\ValidationException;

test("it can be receive forgot password email", function () {
    $customerServiceMock = Mockery::mock(CustomerService::class);
    $userServiceMock = Mockery::mock(UserService::class);

    $email = "example@email.com";
    $user = User::factory()->create(['email' => $email]);

    $userServiceMock->shouldReceive("getUserByEmailWithoutException")
        ->withArgs([$email])
        ->once()
        ->andReturn($user);

    Password::shouldReceive('createToken')
        ->once()->with($user)
        ->andReturn('fake-token');

    Mail::fake();

    $authService = new AuthService(
        $customerServiceMock,
        $userServiceMock
    );

    $authService->forgotPassword($email);

    Mail::assertSent(CustomResetPasswordMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test("try to receive forgot password email with invalid email", function () {
    $customerServiceMock = Mockery::mock(CustomerService::class);
    $userServiceMock = Mockery::mock(UserService::class);

    $email = "exampleUserNotExists@email.com";
    $user = User::factory()->create(['email' => $email]);

    $userServiceMock->shouldReceive("getUserByEmailWithoutException")
        ->withArgs([$email])
        ->once()
        ->andReturnNull();

    Mail::fake();

    $authService = new AuthService(
        $customerServiceMock,
        $userServiceMock
    );

    $authService->forgotPassword($email);

    Mail::assertNothingSent(CustomResetPasswordMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test("sent a invalid email or null", function () {
    $customerServiceMock = Mockery::mock(CustomerService::class);
    $userServiceMock = Mockery::mock(UserService::class);

    $email = "invalidEmail";

    $authService = new AuthService(
        $customerServiceMock,
        $userServiceMock
    );

    try {
        $response = $authService->forgotPassword($email);
    } catch (ValidationException $e) {
        expect($e->errors())
            ->toHaveKey("email");
    }
});
