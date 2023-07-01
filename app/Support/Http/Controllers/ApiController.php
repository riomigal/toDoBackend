<?php

namespace Support\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiController extends Controller
{
    public function sendResponse(string $message, JsonResource|array|null $data, int $code = 200): JsonResponse
    {
        $response = [
            'message' => $message,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    public function sendError(string $message, array|null $errors = null, int $code = 404): JsonResponse
    {
        $response = [
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
