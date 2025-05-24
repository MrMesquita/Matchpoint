<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "RegisterRequest",
    required: ["name", "email", "password"],
    properties: [
        new OA\Property(property: "name", type: "string", example: "John Doe"),
        new OA\Property(property: "email", type: "string", format: "email", example: "johndoe@example.com"),
        new OA\Property(property: "password", type: "string", format: "password", example: "12345678"),
    ]
)]
class RegisterRequest
{
}
