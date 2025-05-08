<?php

use App\Dtos\ResetPasswordDTO;
use App\Models\User;
use App\Services\AuthService;
use App\Services\CustomerService;
use App\Services\UserService;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test("it can be reset password", function () {
    $customerServiceMock = Mockery::mock(CustomerService::class);
    $userServiceMock = Mockery::mock(UserService::class);

    $userMock = Mockery::mock(User::class)->makePartial();
    $userMock->email = 'user@example.com';

    $dto = new ResetPasswordDTO(
        email: $userMock->email,
        token: 'valid.token',
        password: 'password',
        password_confirmation: 'password'
    );

    $userMock->shouldReceive('forceFill')->once()->andReturnSelf();
    $userMock->shouldReceive('save')->once()->andReturn(true);
    $userMock->shouldReceive('tokens')->once()->andReturn(
        Mockery::mock()->shouldReceive('delete')->once()->getMock()
    );

    Password::shouldReceive('reset')
        ->once()
        ->andReturnUsing(function ($credentials, $callback) use ($userMock) {
            $callback($userMock, $credentials['password']);
            return PasswordBroker::PASSWORD_RESET;
        });

    $authService = new AuthService($customerServiceMock, $userServiceMock);
    $response = $authService->resetPassword($dto);

    expect($response)->toBeNull();
});

test("try to reset password with invalid token", function () {
    $customerServiceMock = Mockery::mock(CustomerService::class);
    $userServiceMock = Mockery::mock(UserService::class);

    $userMock = Mockery::mock(User::class);
    $userMock->shouldReceive('forceFill')->once()->andReturnSelf();
    $userMock->shouldReceive('save')->once();
    $userMock->shouldReceive('tokens')->andReturnSelf();
    $userMock->shouldReceive('delete')->once();

    $dto = new ResetPasswordDTO(
        email: 'user@example.com',
        token: 'invalid.token',
        password: 'password',
        password_confirmation: 'password'
    );

    Password::shouldReceive('reset')
        ->once()
        ->andReturnUsing(function ($credentials, $callback) use ($userMock) {
            $callback($userMock, $credentials['password']);
            return PasswordBroker::INVALID_TOKEN;
        });

    $authService = new AuthService($customerServiceMock, $userServiceMock);

    try {
        $authService->resetPassword($dto);
        $this->fail('ValidationException was not thrown');
    } catch (ValidationException $e) {
        expect($e->errors())->toHaveKey('token');
        expect($e->errors()['token'][0])->toBe('This password reset token is invalid or expired.');
    }
});

test('try to reset password with invalid user', function () {
    $customerServiceMock = Mockery::mock(CustomerService::class);
    $userServiceMock = Mockery::mock(UserService::class);

    $userMock = Mockery::mock(User::class);
    $userMock->shouldReceive('forceFill')->once()->andReturnSelf();
    $userMock->shouldReceive('save')->once();
    $userMock->shouldReceive('tokens')->andReturnSelf();
    $userMock->shouldReceive('delete')->once();

    $dto = new ResetPasswordDTO(
        email: 'example.invalid@user.com',
        token: 'invalid.token',
        password: 'password',
        password_confirmation: 'password'
    );

    Password::shouldReceive('reset')
        ->once()
        ->andReturnUsing(function ($credentials, $callback) use ($userMock) {
            $callback($userMock, $credentials['password']);
            return PasswordBroker::INVALID_USER;
        });

    $authService = new AuthService($customerServiceMock, $userServiceMock);

    try {
        $authService->resetPassword($dto);
        $this->fail('ValidationException was not thrown');
    } catch (ValidationException $e) {
        expect($e->errors())->toHaveKey('email');
        expect($e->errors()['email'][0])->toBe('This user does not exist.');
    }
});
