<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "LoginResponse",
    properties: [
        new OA\Property(property: "token", type: "string", example: "eyJ0eXAiOiJKV1QiLCJhbGciOiJ..."),
    ],
    type: "object"
)]
class LoginResponse
{
}
