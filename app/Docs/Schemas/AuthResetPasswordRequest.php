<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AuthResetPasswordRequest",
    required: ["email", "password", "token"],
    properties: [
        new OA\Property(property: "email", type: "string", format: "email", example: "admin@email.com"),
        new OA\Property(property: "password", type: "string", format: "password", example: "12345678"),
        new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "12345678"),
        new OA\Property(property: "token", type: "string", format: "token", example: "1234534gHksE.44"),
    ]
)]
class AuthResetPasswordRequest
{
}
