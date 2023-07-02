<?php

namespace Domain\Task\Actions;

use Domain\Task\Models\Category;
use Domain\Task\Models\Priority;
use Domain\Task\Models\Task;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AddTaskAction
{

    public function execute(string $name, string $description, User $user, Collection $categories, Priority $priority): Task
    {
        $task = Task::create([
            'name' => $name,
            'description' => $description,
            'user_id' => $user->id,
            'priority_id' => $priority->id,
            'completed' => false
        ]);
        
        $task->categories()->syncWithoutDetaching($categories->pluck('id')->toArray());

        return $task;
    }
}
