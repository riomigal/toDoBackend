<?php

namespace Domain\Task\Policies;


use Domain\Task\Models\Category;
use Domain\User\Models\User;

class CategoryPolicy
{

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Category $category): bool
    {
        return $category->users()->where('user_id', $user->id)->first();
    }

    public function create(User $user): bool
    {
        return true;
    }


    public function update(User $user, Category $category): bool
    {
        return $this->belongsToUser($user, $category);
    }


    public function delete(User $user, Category $category): bool
    {
        return $this->belongsToUser($user, $category);
    }


    public function restore(User $user, Category $category): bool
    {
        return $this->belongsToUser($user, $category);
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $this->belongsToUser($user, $category);
    }

    protected function belongsToUser(User $user, Category $category) {
        return $category->users()->where('user_id', $user->id)->first();
    }
}
