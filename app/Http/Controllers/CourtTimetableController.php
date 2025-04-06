<?php

namespace App\Http\Controllers;

use App\Services\CourtTimetableService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Courts")]
class CourtTimetableController
{
    private CourtTimetableService $courtTimetableService;

    public function __construct(
        CourtTimetableService $courtTimetableService
    ) {
        $this->courtTimetableService = $courtTimetableService;
    }

    #[OA\Get(
        path: '/api/courts/{courtId}/timetables',
        summary: 'List timetables for a specific court',
        tags: ['Courts'],
        parameters: [
            new OA\Parameter(
                name: 'courtId',
                description: 'ID of the court',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of timetables',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/CourtTimetableResource')
                )
            )
        ]
    )]
    public function index(string $courtId): JsonResponse
    {
        $timetables = $this->courtTimetableService->getCourtTimetables($courtId);
        return success_response($timetables);
    }

    #[OA\Post(
        path: '/api/courts/{courtId}/timetables',
        summary: 'Create a new timetable for a court',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['day_of_week', 'start_time', 'end_time', 'status'],
                properties: [
                    new OA\Property(property: 'day_of_week', type: 'integer', example: 1),
                    new OA\Property(property: 'start_time', type: 'string', example: '08:00'),
                    new OA\Property(property: 'end_time', type: 'string', example: '10:00'),
                    new OA\Property(property: 'status', type: 'string', enum: ['available', 'busy'], example: 'available'),
                ]
            )
        ),
        tags: ['Courts'],
        parameters: [
            new OA\Parameter(
                name: 'courtId',
                description: 'ID of the court',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Timetable created successfully'
            )
        ]
    )]
    public function store(Request $request, string $courtId): JsonResponse
    {
        $timetable = $this->courtTimetableService->save($request, $courtId);
        return success_response($timetable, "Timetable created successfully", Response::HTTP_CREATED);
    }

    #[OA\Delete(
        path: '/api/courts/{court}/timetables/{timetable}',
        summary: 'Delete a specific timetable',
        tags: ['Courts'],
        parameters: [
            new OA\Parameter(
                name: 'court',
                description: 'ID of the court',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'timetable',
                description: 'ID of the timetable to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Timetable deleted successfully'
            )
        ]
    )]
    public function destroy(string $timetable): JsonResponse
    {
        $this->courtTimetableService->deleteTimetable($timetable);
        return success_response(null, null, Response::HTTP_NO_CONTENT);
    }
}
