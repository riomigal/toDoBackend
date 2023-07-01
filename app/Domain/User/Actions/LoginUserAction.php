<?php

namespace Domain\User\Actions;

use Domain\User\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginUserAction
{
    public function execute(string $email, #[\SensitiveParameter] string $password): User|null
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return null;
        }

        return Auth::user();
    }
}
