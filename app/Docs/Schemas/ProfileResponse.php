<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProfileResponse',
    required: ['id', 'name', 'surname', 'phone', 'email'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'João'),
        new OA\Property(property: 'surname', type: 'string', example: 'Silva'),
        new OA\Property(property: 'phone', type: 'string', example: '+55 11 99999-9999'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'joao.silva@email.com'),
    ],
    type: 'object'
)]
class ProfileResponse
{
}
