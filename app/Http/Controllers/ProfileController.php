<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Items;
use OpenApi\Attributes as OA;
use function success_response;

#[OA\Tag(name: "Profiles")]
class ProfileController
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    #[OA\Get(
        path: "/api/v1/profiles/",
        description: "Get a profile",
        summary: "Get a profile",
        security: [['bearerAuth' => []]],
        tags: ["profiles"],
        responses: [
            new OA\Response(
                response: 200,
                description: "List returned successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(
                            property: "results",
                            type: "array",
                            items: new Items(ref: "#/components/schemas/ProfileResponse")
                        )
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 401,
                description: "Tries access without login",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "success", type: "boolean", example: false),
                            new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: "Admin not found",
                content: new OA\JsonContent(ref: "#/components/schemas/NotFound")
            )
        ]
    )]
    public function profile(): JsonResponse
    {
        $data = $this->profileService->getProfileData();
        return success_response($data);
    }

    #[OA\Put(
        path: "/api/v1/profiles",
        description: "Edit a profile",
        summary: "Edit a profile",
        security: [['bearerAuth' => []]],
        tags: ["profiles"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Profile updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Update profile.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Tries access without login",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "success", type: "boolean", example: false),
                            new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: "User not found",
                content: new OA\JsonContent(ref: "#/components/schemas/NotFound")
            )
        ]
    )]
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $this->profileService->updateProfile($request->toDTO());
        return success_response(null, "Profile updated successfully");
    }

    #[Delete(
        path: "/api/v1/profiles",
        description: "Delete a profile",
        summary: "Delete a profile",
        security: [['bearerAuth' => []]],
        tags: ["profiles"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Profile deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Deleted profile.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Tries access without login",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "success", type: "boolean", example: false),
                            new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: "User not found Or User Already deleted",
                content: new OA\JsonContent(ref: "#/components/schemas/NotFound")
            )
        ]
    )]
    public function deleteProfile(): JsonResponse
    {
        $this->profileService->deleteProfile();
        return success_response(null, "Profile deleted successfully");
    }
}
