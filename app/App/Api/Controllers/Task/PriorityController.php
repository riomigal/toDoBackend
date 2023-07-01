<?php

namespace App\Api\Controllers\Task;

use App\Api\Resources\PriorityResource;
use Domain\Task\Models\Priority;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Support\Http\Controllers\ApiController;

class PriorityController extends ApiController
{
    public function __invoke(): JsonResponse
    {
        return Cache::rememberForever('cache_priorities', function() {
            return $this->sendResponse('',  PriorityResource::collection(Priority::all()));
        });
    }
}
