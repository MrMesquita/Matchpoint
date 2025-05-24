<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CourtTimetableResource',
    required: ['id', 'court_id', 'day_of_week', 'start_time', 'end_time'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 10),
        new OA\Property(property: 'court_id', type: 'integer', example: 1),
        new OA\Property(property: 'day_of_week', type: 'string', example: 'monday'),
        new OA\Property(property: 'start_time', type: 'string', format: 'HH:mm:ss', example: '08:00:00'),
        new OA\Property(property: 'end_time', type: 'string', format: 'HH:mm:ss', example: '18:00:00'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-04-01T10:00:00Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-04-01T10:30:00Z'),
    ],
    type: 'object'
)]
class CourtTimetableResource {}
