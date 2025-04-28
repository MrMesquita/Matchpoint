<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Auth")]
class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[OA\Post(
        path: "/api/v1/auth/login",
        description: "Authenticates the user and returns a token",
        summary: "User Login",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(ref: "#/components/schemas/LoginRequest")
            )
        ),
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Login successful",
                content: new OA\JsonContent(ref: "#/components/schemas/LoginResponse")
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")
            )
        ]
    )]
    public function login(Request $request): JsonResponse
    {
        $token = $this->authService->attemptLogin($request->only(['email', 'password']));
        return success_response(['token' => $token]);
    }

    #[OA\Post(
        path: "/api/v1/auth/logout",
        description: "Logs out the user and invalidates the token",
        summary: "User Logout",
        security: [['bearerAuth' => []]],
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Logout successful"
            )
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request);
        return success_response(null, 'Logged out successfully');
    }

    #[OA\Post(
        path: "/api/v1/auth/register",
        description: "Creates a new user",
        summary: "User Registration",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(ref: "#/components/schemas/RegisterRequest")
            )
        ),
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Registration successful",
                content: new OA\JsonContent(ref: "#/components/schemas/LoginResponse")
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(ref: "#/components/schemas/Unauthorized")
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")
            ),
        ]
    )]
    public function register(Request $request): JsonResponse
    {
        $token = $this->authService->registerCustomer($request);
        return success_response(['token' => $token], "Registered successfully", Response::HTTP_CREATED);
    }

    #[OA\Post(
        path: "/api/v1/auth/forgot-password",
        description: "User can receive a email to recovery your password",
        summary: "Forgot password",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(ref: "#/components/schemas/ForgotPasswordRequest")
            )
        ),
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Forgot password successful",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "message",
                                type: "string",
                                example: "Email sent successfully"
                            )
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")
            )
        ]
    )]
    public function forgotPassword(Request $request): JsonResponse
    {
        $this->authService->forgotPassword($request);
        return success_response("Email sent successfully");
    }

    #[OA\Post(
        path: "/api/v1/auth/reset-password",
        description: "Reset password",
        summary: "Reset password",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(ref: "#/components/schemas/AuthResetPasswordRequest")
            )
        ),
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Reset password successful",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "message",
                                type: "string",
                                example: "Password reset successfully"
                            )
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validation error",
                content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")
            )
        ]
    )]
    public function resetPassword(Request $request): JsonResponse
    {
        $this->authService->resetPassword($request);
        return success_response("Password reset successfully");
    }
}
