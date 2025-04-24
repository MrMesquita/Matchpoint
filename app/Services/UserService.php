<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getUserByEmail(string $email)
    {
        return $this->findUserOrFail($email);
    }

    private function findUserOrFail($email): User
    {
        return User::where('email', $email)->first()
            ?? throw new UserNotFoundException();
    }
}
