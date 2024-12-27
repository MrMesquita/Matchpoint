<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $token = $this->authService->attemptLogin($request->only(['email', 'password']));
        return success_response(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);
        return success_response(null, 'logged out successfully');
    }

    public function register(Request $request)
    {
        $token = $this->authService->registerCustomer($request);
        return success_response(['token' => $token], "Registred successfully", Response::HTTP_CREATED);
    }
}
