<?php

namespace Domain\Task\Policies;


use Domain\Task\Models\Task;
use Domain\User\Models\User;

class TaskPolicy
{

    public function viewAny(User $user): bool
    {
        return true;
    }


    public function view(User $user, Task $task): bool
    {
        return $this->belongsToUser($user, $task);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Task $task): bool
    {
        return $this->belongsToUser($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->belongsToUser($user, $task);
    }

    public function restore(User $user, Task $task): bool
    {
        return $this->belongsToUser($user, $task);
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $this->belongsToUser($user, $task);
    }

    protected function belongsToUser(User $user, Task $task): bool {

        return $user->is($task->user);
    }
}
