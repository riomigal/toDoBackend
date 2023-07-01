<?php

namespace App\Api\Controllers\Auth;

use App\Api\Requests\LoginRequest;
use App\Api\Requests\RegisterRequest;
use Domain\User\Actions\LoginUserAction;
use Domain\User\Actions\RegisterUserAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Support\Http\Controllers\ApiController;

class AuthController extends ApiController
{

    public function login(LoginRequest $request, LoginUserAction $loginUserAction): JsonResponse
    {
        $data = $request->validated();

        $user = $loginUserAction->execute($data['email'], $data['password']);

        if (!$user) {
            return $this->sendError(__('Login failed! Credentials are invalid!'), null, 401);
        }

        $response = [
            'user' => $user,
            'authorization' => [
                'token' => $user->createToken('apiAuthToken')->plainTextToken,
                'type' => 'bearer',
            ]
        ];

        return $this->sendResponse(__('Login successful!'), $response);
    }

    public function register(RegisterRequest $request, RegisterUserAction $registerUserAction): JsonResponse
    {
        $data = $request->validated();

        $user = $registerUserAction->execute($data['name'], $data['email'], $data['password']);

        return $this->sendResponse(__('Account created!'), ['user' => $user], 201);

    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();
        return $this->sendResponse(__('Logout successful'), 200);
    }
}
