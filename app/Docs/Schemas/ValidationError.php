<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ValidationError",
    properties: [
        new OA\Property(property: "message", type: "string", example: "The given data was invalid."),
        new OA\Property(
            property: "errors",
            type: "object",
            example: [
                "email" => ["The email field is required."]
            ]
        )
    ],
    type: "object"
)]
class ValidationError
{
}
