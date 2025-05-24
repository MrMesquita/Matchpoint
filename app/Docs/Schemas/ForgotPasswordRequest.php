<?php

namespace App\Docs\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ForgotPasswordRequest",
    required: ["email"],
    properties: [new OA\Property(property: "email", type: "string", example: "example@email.com")],
    type: "object"
)]
class ForgotPasswordRequest
{
}
