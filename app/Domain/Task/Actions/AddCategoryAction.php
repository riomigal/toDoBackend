<?php

namespace Domain\Task\Actions;

use Domain\Task\Models\Category;
use Domain\User\Models\User;

class AddCategoryAction
{
    public function execute(string $name, User $user): Category|null
    {
        $category = Category::firstOrCreate([
            'name' => $name,
        ]);

        $category->users()->syncWithoutDetaching([$user->id]);

        return $category;
    }
}
