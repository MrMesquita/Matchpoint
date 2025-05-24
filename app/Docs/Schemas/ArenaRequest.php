<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ArenaRequest",
    required: ["name", "street", "number", "neighborhood", "city", "state", "zip_code"],
    properties: [
        new OA\Property(property: "name", type: "string", example: "Arena Nova"),
        new OA\Property(property: "street", type: "string", example: "Rua da Arena"),
        new OA\Property(property: "number", type: "string", example: "123"),
        new OA\Property(property: "neighborhood", type: "string", example: "Centro"),
        new OA\Property(property: "city", type: "string", example: "Recife"),
        new OA\Property(property: "state", type: "string", example: "PE"),
        new OA\Property(property: "zip_code", type: "string", example: "52000-000"),
        new OA\Property(property: "admin_id", type: "integer", example: 1),
    ],
    type: "object"
)]
class ArenaRequest
{
}
