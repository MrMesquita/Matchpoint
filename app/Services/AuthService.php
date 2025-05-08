<?php

namespace App\Services;

use App\Dtos\ResetPasswordDTO;
use App\Exceptions\UserNotFoundException;
use App\Mail\CustomResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthService
{
    private CustomerService $customerService;

    private UserService $userService;

    public function __construct(
        CustomerService $customerService,
        UserService     $userService
    )
    {
        $this->customerService = $customerService;
        $this->userService = $userService;
    }

    public function attemptLogin(array $credentials): string
    {
        $this->validateCredentials($credentials);
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'login' => ['Email and/or password invalids'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();
        return $user->createToken($user->name, ['*'])->plainTextToken;
    }

    private function validateCredentials(array $credentials)
    {
        validator($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ])->validate();
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
    }

    public function registerCustomer(Request $request)
    {
        $customer = $this->customerService->createCustomer($request);
        $password = $request->input('password');

        return $this->attemptLogin([
            'email' => $customer->email,
            'password' => $password,
        ]);
    }

    public function forgotPassword(string $email): void
    {
        validator(['email' => $email], [
            'email' => 'required|email'
        ])->validate();

        $user = $this->userService->getUserByEmailWithoutException($email);
        if ($user) {
            $token = Password::createToken($user);
            $urlRecoveryToken = config('app.client_url') . "/reset-password/$token";
            Mail::to($user->email)->send(new CustomResetPasswordMail($user, $urlRecoveryToken));
        }
    }

    public function resetPassword(ResetPasswordDTO $dto): void
    {
        $status = Password::reset((array)$dto, function (User $user, string $password) use ($dto) {
            $user->forceFill([
                'password' => bcrypt($password),
            ])->save();

            $user->tokens()->delete();
        });

        if ($status === Password::INVALID_TOKEN) {
            throw ValidationException::withMessages([
                'token' => ['This password reset token is invalid or expired.'],
            ]);
        } else if ($status === Password::INVALID_USER) {
            throw ValidationException::withMessages([
                'email' => ['This user does not exist.'],
            ]);
        }
    }
}
