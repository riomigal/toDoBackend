<?php

namespace Domain\Task\Actions;

use Domain\Task\Models\Task;

class MarkTaskCompleteAction
{
    public function execute(Task $task, bool $complete = true): Task
    {
        $task->update([
            'completed' => $complete
        ]);

        return $task;
    }
}
