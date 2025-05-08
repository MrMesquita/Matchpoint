<?php

namespace App\Dtos;

class ResetPasswordDTO
{
    public function __construct(
        string $email,
        string $token,
        string $password,
        string $password_confirmation
    )
    {
        $this->email = $email;
        $this->token = $token;
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
    }
}
