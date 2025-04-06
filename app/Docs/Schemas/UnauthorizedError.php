<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Unauthorized',
    description: 'Response for unauthorized access attempts',
    required: ['success', 'message'],
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: false),
        new OA\Property(property: 'message', type: 'string', example: 'Unauthorized.')
    ],
    type: 'object'
)]
class UnauthorizedError
{
}
