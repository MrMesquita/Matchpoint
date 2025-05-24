<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminResource',
    required: ['id', 'name', 'email', 'type', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: 'f36c8a9c-1f85-4a38-bc92-6e0e9b99d021'),
        new OA\Property(property: 'name', type: 'string', example: 'Maria das Graças'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'maria@example.com'),
        new OA\Property(property: 'type', type: 'string', example: 'system'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2025-04-01T08:30:00Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2025-04-01T08:45:00Z'),
    ],
    type: 'object'
)]
class AdminResource
{
}
