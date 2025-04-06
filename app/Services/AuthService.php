<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function attemptLogin(array $credentials): string
    {
        $this->validateCredentials($credentials);
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'login' => ['Email e/ou senha inválido'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();
        return $user->createToken($user->name, ['*'], now()->addDays(3))->plainTextToken;
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
}
