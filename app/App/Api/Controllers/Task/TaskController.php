<?php

namespace App\Api\Controllers\Task;

use App\Api\Requests\AddTaskRequest;
use App\Api\Resources\TaskResource;
use Domain\Task\Actions\AddMultipleCategoriesAction;
use Domain\Task\Actions\AddTaskAction;
use Domain\Task\Actions\DeleteTaskAction;
use Domain\Task\Actions\MarkTaskCompleteAction;
use Domain\Task\Models\Priority;
use Domain\Task\Models\Task;
use Domain\Task\QueryBuilders\TaskQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Support\Http\Controllers\ApiController;

class TaskController extends ApiController
{
    public function store(AddTaskRequest $addTaskRequest, AddTaskAction $addTaskAction, AddMultipleCategoriesAction $addMultipleCategoriesAction): JsonResponse
    {
        $this->authorize('create', Task::class);

        $data = $addTaskRequest->validated();
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $categories = $addMultipleCategoriesAction->execute($data['categories'], $user);
            $task = $addTaskAction->execute($data['name'], $data['description'], $user, $categories, Priority::find($data['priority_id']));
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return $this->sendResponse(__('Task added!'), new TaskResource($task), 201);
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Task::class);
        return $this->sendResponse('', TaskResource::collection((new TaskQuery())->get()));
    }

    public function delete(DeleteTaskAction $deleteTaskAction, Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $deleteTaskAction->execute($task);
        return response()->json([], 204);
    }

    public function complete(MarkTaskCompleteAction $markTaskCompleteAction, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $markTaskCompleteAction->execute($task);
        return $this->sendResponse(__('Task marked as complete!'), new TaskResource($task->fresh()));
    }

    public function pending(MarkTaskCompleteAction $markTaskCompleteAction, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $markTaskCompleteAction->execute($task, false);
        return $this->sendResponse(__('Task marked as pending!'), new TaskResource($task->fresh()));
    }
}
