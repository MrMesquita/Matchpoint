<?php

namespace App\Services;

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

    public function attemptLogin(array $credentials)
    {
        $this->validateCredentials($credentials);
 
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'login' => ['Email e/ou senha inválido'],
            ]);
        }

        /** @var \App\Models\User $user */
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
        $password = $request->input('password');
        $customer = $this->customerService->createCustomer($request);
    
        return $this->attemptLogin([
            'email' => $customer->email,
            'password' => $password,
        ]);
    }
}
