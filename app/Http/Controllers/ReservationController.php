<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Reservations")]
class ReservationController
{
    private ReservationService $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    #[OA\Get(
        path: '/api/v1/reservations',
        summary: 'List all reservations',
        security: [['bearerAuth' => []]],
        tags: ['Reservations'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of reservations returned successfully',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/ReservationResource')
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        $reservations = $this->reservationService->getAllReservations();
        return success_response($reservations);
    }

    #[OA\Post(
        path: '/api/v1/reservations',
        summary: 'Create a new reservation',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['court_id', 'date', 'start_time', 'end_time'],
                properties: [
                    new OA\Property(property: 'court_id', type: 'integer', example: 1),
                    new OA\Property(property: 'date', type: 'string', format: 'date', example: '2024-04-10'),
                    new OA\Property(property: 'start_time', type: 'string', format: 'HH:mm:ss', example: '14:00:00'),
                    new OA\Property(property: 'end_time', type: 'string', format: 'HH:mm:ss', example: '15:00:00'),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        enum: ['pending', 'confirmed', 'cancelled'],
                        example: 'pending'
                    )
                ],
                type: 'object'
            )
        ),
        tags: ['Reservations'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Reservation created successfully'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            )
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $reservation = $this->reservationService->save($request);
        return success_response($reservation, "Reservation created successfully", Response::HTTP_CREATED);
    }

    #[OA\Get(
        path: '/api/v1/reservations/{id}',
        summary: 'Get a reservation by ID',
        security: [['bearerAuth' => []]],
        tags: ['Reservations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Reservation ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reservation retrieved successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/ReservationResource')
            ),
            new OA\Response(response: 404, description: 'Reservation not found')
        ]
    )]
    public function show(string $reservation): JsonResponse
    {
        $reservation = $this->reservationService->getReservationById($reservation);
        return success_response($reservation);
    }

    #[OA\Post(
        path: '/api/v1/reservations/{id}/confirm',
        summary: 'Confirm a reservation (admin only)',
        security: [['bearerAuth' => []]],
        tags: ['Reservations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Reservation ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reservation confirmed successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/ReservationResource')
            ),
            new OA\Response(response: 403, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Reservation not found')
        ]
    )]
    public function confirmReservation(string $reservation): JsonResponse
    {
        $reservation = $this->reservationService->confirmReservation($reservation);
        return success_response($reservation, "Reservation confirmed successfully");
    }

    #[OA\Delete(
        path: '/api/v1/reservations/{id}',
        summary: 'Cancel a reservation (admin or customer)',
        security: [['bearerAuth' => []]],
        tags: ['Reservations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Reservation ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reservation canceled successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/ReservationResource')
            ),
            new OA\Response(response: 404, description: 'Reservation not found')
        ]
    )]
    public function destroy(string $reservation)
    {
        $reservation = $this->reservationService->cancelReservation($reservation);
        return success_response($reservation, "Reservation canceled successfully");
    }
}
