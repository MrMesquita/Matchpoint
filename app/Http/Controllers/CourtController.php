<?php

namespace App\Http\Controllers;

use App\Services\CourtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Courts')]
class CourtController
{
    private CourtService $courtService;

    public function __construct(CourtService $courtService)
    {
        $this->courtService = $courtService;
    }

    #[OA\Get(
        path: 'api/v1/courts',
        summary: 'List all courts',
        security: [['bearerAuth' => []]],
        tags: ['Courts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Courts retrieved successfully',
                content: new OA\JsonContent(ref: "#/components/schemas/CourtResource")
            ),
            new OA\Response(response: 401, description: 'Unauthorized')
        ]
    )]
    public function index(): JsonResponse
    {
        $courts = $this->courtService->getAllCourts();
        return success_response($courts);
    }

    #[OA\Post(
        path: 'api/v1/courts',
        summary: 'Create a new court',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CourtRequest')
        ),
        tags: ['Courts'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Court created successfully'
            ),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $court = $this->courtService->save($request);
        return success_response($court, "Court create succesfully", Response::HTTP_CREATED);
    }

    #[OA\Get(
        path: 'api/v1/courts/{id}',
        summary: 'Get a specific court',
        security: [['bearerAuth' => []]],
        tags: ['Courts'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Court ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Court retrieved successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/CourtResource')
            ),
            new OA\Response(response: 404, description: 'Court not found')
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $court = $this->courtService->getCourtById($id);
        return success_response($court);
    }

    #[OA\Put(
        path: 'api/v1/courts/{id}',
        summary: 'Update a court',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CourtRequest')
        ),
        tags: ['Courts'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Court ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Court updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/CourtResource')
            ),
            new OA\Response(response: 404, description: 'Court not found')
        ]
    )]
    public function update(Request $request, string $id)
    {
        $court = $this->courtService->updateCourt($request, $id);
        return success_response($court);
    }

    #[OA\Delete(
        path: 'api/v1/courts/{id}',
        summary: 'Delete a court',
        security: [['bearerAuth' => []]],
        tags: ['Courts'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Court ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'int')
            )
        ],
        responses: [
            new OA\Response(response: 204, description: 'Court deleted successfully'),
            new OA\Response(response: 404, description: 'Court not found')
        ]
    )]
    public function destroy(string $id)
    {
        $this->courtService->deleteCourt($id);
        return success_response(null, null, Response::HTTP_NO_CONTENT);
    }
}
