<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CourtResource',
    description: 'Quadra esportiva disponível para reservas',
    required: ['id', 'name', 'type', 'status', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Quadra de Tênis 1'),
        new OA\Property(property: 'type', type: 'string', example: 'Tênis'),
        new OA\Property(property: 'status', type: 'string', example: 'available'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-04-01T10:00:00Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-04-01T11:00:00Z'),
    ],
    type: 'object'
)]
class CourtResource
{
}
