<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "LoginRequest",
    required: ["email", "password"],
    properties: [
        new OA\Property(property: "email", type: "string", format: "email", example: "admin@email.com"),
        new OA\Property(property: "password", type: "string", format: "password", example: "12345678"),
    ]
)]
class LoginRequest
{
}
