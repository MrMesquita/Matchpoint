<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ArenaResource",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", example: "Arena Nova"),
        new OA\Property(property: "street", type: "string", example: "Rua da Arena"),
        new OA\Property(property: "number", type: "string", example: "123"),
        new OA\Property(property: "neighborhood", type: "string", example: "Centro"),
        new OA\Property(property: "city", type: "string", example: "Recife"),
        new OA\Property(property: "state", type: "string", example: "PE"),
        new OA\Property(property: "zip_code", type: "string", example: "52000-000"),
        new OA\Property(property: "admin_id", type: "integer", example: 1),
        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2025-01-02T18:46:22.000000Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2025-01-02T18:46:22.000000Z"),
        new OA\Property(property: "deleted_at", type: "string", example: null, nullable: true),
    ],
    type: "object"
)]
class ArenaResource
{
}
