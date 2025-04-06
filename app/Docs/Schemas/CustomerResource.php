<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CustomerResource',
    required: ['id', 'name', 'surname', 'phone', 'email'],
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: 'fa0d70b5-3a20-4220-a7e4-0d5b479e2739'),
        new OA\Property(property: 'name', type: 'string', example: 'John'),
        new OA\Property(property: 'surname', type: 'string', example: 'Doe'),
        new OA\Property(property: 'phone', type: 'string', example: '+5511999999999'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-04-01T10:00:00Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-04-01T11:00:00Z'),
    ],
    type: 'object'
)]
class CustomerResource {}
