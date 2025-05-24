<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NotFound',
    description: 'Resource not found response',
    required: ['success', 'message'],
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: false),
        new OA\Property(property: 'message', type: 'string', example: 'Resource not found')
    ],
    type: 'object'
)]
class NotFoundError
{
}
