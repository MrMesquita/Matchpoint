<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ReservationResource',
    required: ['id', 'court_id', 'user_id', 'date', 'start_time', 'end_time', 'status'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 42),
        new OA\Property(property: 'court_id', type: 'integer', example: 1),
        new OA\Property(property: 'user_id', type: 'integer', example: 3),
        new OA\Property(property: 'date', type: 'string', format: 'date', example: '2024-04-10'),
        new OA\Property(property: 'start_time', type: 'string', format: 'HH:mm:ss', example: '14:00:00'),
        new OA\Property(property: 'end_time', type: 'string', format: 'HH:mm:ss', example: '15:00:00'),
        new OA\Property(property: 'status', type: 'string', enum: ['pending', 'confirmed', 'cancelled'], example: 'confirmed'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-04-01T14:00:00Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-04-01T15:00:00Z'),
    ],
    type: 'object'
)]
class ReservationResource
{
}
