<?php

namespace Domain\User\Actions;

use Domain\User\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(string $name, string $email, #[\SensitiveParameter] string $password): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }
}
