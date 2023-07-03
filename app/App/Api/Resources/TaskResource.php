<?php

namespace App\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'completed' => $this->completed,
            'user' => new UserResource($this->user),
            'priority' => new PriorityResource($this->priority),
            'categories' => CategoryResource::collection($this->categories),
            'created' => $this->created_at->format('M d Y, h:i:s'),
            'updated' => $this->updated_at->format('M d Y, h:i:s'),
        ];
    }
}
