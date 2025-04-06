<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Administrators")]
class AdminController extends BaseController
{
    private AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    #[OA\Get(
        path: "/api/admins",
        summary: "List all administrators",
        security: [["bearerAuth" => []]],
        tags: ["Administrators"],
        responses: [
            new OA\Response(
                response: 200,
                description: "List return successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(
                            property: "results",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/AdminResource")
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Tries access without system login",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "success", type: "boolean", example: false),
                            new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                        ]
                    )
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        return success_response($this->adminService->getAllAdmins());
    }

    #[OA\Post(
        path: "/api/admins",
        summary: "Create a new admin",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Maria das Graças'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'maria@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'securePass123'),
                ],
                type: 'object'
            )
        ),
        tags: ["Administrators"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Admin created successfully",
                content: new OA\JsonContent(ref: "#/components/schemas/AdminResource")
            ),
            new OA\Response(
                response: 400,
                description: "Validation errors",
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
        $admin = $this->adminService->createAdmin($request);
        return success_response($admin, null, Response::HTTP_CREATED);
    }

    #[OA\Get(
        path: "/api/admins/{id}",
        summary: "Get an admin by id",
        security: [["bearerAuth" => []]],
        tags: ["Administrators"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Admin founded successfully",
                content: new OA\JsonContent(ref: "#/components/schemas/AdminResource")
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
        $admin = $this->adminService->getAdminById($id);
        return success_response($admin);
    }

    #[OA\Put(
        path: "/api/admins/{id}",
        summary: "Update admin data by id",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Maria das Graças'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'maria@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'securePass123'),
                ],
                type: 'object'
            )
        ),
        tags: ["Administrators"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Admin successfully updated",
                content: new OA\JsonContent(ref: "#/components/schemas/AdminResource")
            ),
            new OA\Response(
                response: 400,
                description: "Validation errors",
                content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(ref: "#/components/schemas/Unauthorized")
            ),
            new OA\Response(
                response: 404,
                description: "Admin not found",
                content: new OA\JsonContent(ref: "#/components/schemas/NotFound")
            )
        ]
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $admin = $this->adminService->updateAdmin($request, $id);
        return success_response($admin);
    }

    #[OA\Delete(
        path: "/api/admins/{id}",
        summary: "Remove an admin by ID",
        security: [["bearerAuth" => []]],
        tags: ["Administrators"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Destroy an admin successfully"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(ref: "#/components/schemas/Unauthorized")
            )
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $this->adminService->deleteAdmin($id);
        return success_response(null, null, Response::HTTP_NO_CONTENT);
    }
}
