<?php

namespace Domain\Task\Actions;

use Domain\Task\Models\Priority;
use Domain\Task\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class UpdateTaskAction
{

    public function execute(Task $task, string $name, string $description, Collection $categories, Priority $priority): Task
    {
        // To do get previous categories and see if there is a difference and remove categories which are not more used
        // $previousCategories = $task->categories()->get();
        $task->update([
            'name' => $name,
            'description' => $description,
            'priority_id' => $priority->id,
        ]);

        $task->categories()->sync($categories->pluck('id')->toArray());

        return $task;
    }
}
