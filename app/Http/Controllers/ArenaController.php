<?php

namespace App\Http\Controllers;

use App\Services\ArenaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Arenas")]
class ArenaController
{
    private ArenaService $arenaService;

    public function __construct(ArenaService $arenaService)
    {
        $this->arenaService = $arenaService;
    }

    #[OA\Get(
        path: "/api/v1/arenas",
        summary: "List all arenas",
        security: [['bearerAuth' => []]],
        tags: ["Arenas"],
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
                            items: new OA\Items(ref: "#/components/schemas/ArenaResource")
                        )
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(ref: "#/components/schemas/Unauthorized")
            )
        ]
    )]
    public function index(): JsonResponse
    {
        return success_response($this->arenaService->getAllArenas());
    }

    #[OA\Post(
        path: "/api/v1/arenas",
        summary: "Create a new arena",
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(ref: "#/components/schemas/ArenaRequest")
            )
        ),
        tags: ["Arenas"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Arena created successfully",
                content: new OA\JsonContent(ref: "#/components/schemas/ArenaResource")
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(ref: "#/components/schemas/Unauthorized")
            )
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $arena = $this->arenaService->save($request);
        return success_response($arena, "Arena created successfully", Response::HTTP_CREATED);
    }

    #[OA\Get(
        path: "/api/v1/arenas/{id}",
        summary: "Find arena by id",
        security: [['bearerAuth' => []]],
        tags: ["Arenas"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the arena",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", example: "1")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Returned successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(
                            property: "results",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/ArenaResource")
                        )
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 404,
                description: "Admin not found",
                content: new OA\JsonContent(ref: "#/components/schemas/NotFound")
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(ref: "#/components/schemas/Unauthorized")
            )
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $arena = $this->arenaService->getArenaById($id);
        return success_response($arena);
    }

    #[OA\Get(
        path: "/api/v1/arenas/{id}/courts",
        summary: "Get courts by arena ID",
        security: [['bearerAuth' => []]],
        tags: ["Arenas"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the arena",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", example: "1")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of courts for the specified arena",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(
                            property: "results",
                            type: "array",
                            items: new OA\Items(
                                ref: "#/components/schemas/CourtResource"
                            )
                        )
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 404,
                description: "Arena not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Arena not found.")
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(ref: "#/components/schemas/Unauthorized")
            )
        ]
    )]
    public function courts(string $id): JsonResponse
    {
        $arena = $this->arenaService->getCourts($id);
        return success_response($arena);
    }

    #[OA\Put(
        path: "/api/v1/arenas/{id}",
        summary: "Update an existing arena",
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(ref: "#/components/schemas/ArenaRequest")
            )
        ),
        tags: ["Arenas"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the arena to update",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", example: "1")
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Arena updated successfully",
                content: new OA\JsonContent(ref: "#/components/schemas/ArenaResource")
            ),
            new OA\Response(
                response: 400,
                description: "Validation error",
                content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(ref: "#/components/schemas/Unauthorized")
            ),
            new OA\Response(
                response: 404,
                description: "Arena not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Arena not found.")
                    ],
                    type: "object"
                )
            )
        ]
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $arena = $this->arenaService->updateArena($request, $id);
        return success_response($arena);
    }

    #[OA\Delete(
        path: "/api/v1/arenas/{id}",
        summary: "Delete an existing arena",
        security: [['bearerAuth' => []]],
        tags: ["Arenas"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the arena to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", example: "1")
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Arena deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Arena deleted successfully.")
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 404,
                description: "Arena not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Arena not found.")
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized access (unauthenticated)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                    ],
                    type: "object"
                )
            )
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->arenaService->deleteArena($id);
        return success_response(null, null, Response::HTTP_NO_CONTENT);
    }
}
