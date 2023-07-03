<?php

namespace App\Api\Controllers\Auth;

use App\Api\Requests\LoginRequest;
use App\Api\Requests\RegisterRequest;
use Domain\User\Actions\LoginUserAction;
use Domain\User\Actions\RegisterUserAction;
use Domain\User\Models\User;
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

        return $this->sendResponse(__('Login successful!'), $this->getTokenResponse($user));
    }

    public function register(RegisterRequest $request, RegisterUserAction $registerUserAction): JsonResponse
    {
        $data = $request->validated();

        $user = $registerUserAction->execute($data['name'], $data['email'], $data['password']);

        return $this->sendResponse(__('Account created!'), $this->getTokenResponse($user), 201);

    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();
        return $this->sendResponse(__('Logout successful'), [], 200);
    }

    protected function getTokenResponse(User $user): array
    {
        return [
            'user' => $user,
            'authorization' => [
                'token' => $user->createToken('apiAuthToken')->plainTextToken,
                'type' => 'Bearer',
            ]
        ];
    }
}
