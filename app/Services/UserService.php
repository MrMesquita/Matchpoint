<?php

namespace App\Services;

use App\Models\User;
use App\Exceptions\UserNotFoundException;

class UserService
{
    public function getUserByEmail(string $email): User
    {
        return $this->findUserOrFail($email);
    }

    private function findUserOrFail($email): User
    {
        return User::where('email', $email)->first()
            ?? throw new UserNotFoundException();
    }

    public function getUserByEmailWithoutException(string $email): User|null
    {
        return User::where('email', $email)->first();
    }
}
