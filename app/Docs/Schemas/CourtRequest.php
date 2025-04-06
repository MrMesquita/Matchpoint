<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CourtRequest',
    required: ['name', 'type', 'status'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Quadra de Tênis 1'),
        new OA\Property(property: 'type', type: 'string', example: 'Tênis'),
        new OA\Property(
            property: 'status',
            type: 'string',
            enum: ['available', 'unavailable'],
            example: 'available'
        ),
    ],
    type: 'object'
)]
class CourtRequest
{
}
