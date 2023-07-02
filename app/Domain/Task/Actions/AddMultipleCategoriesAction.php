<?php

namespace Domain\Task\Actions;

use Domain\Task\Models\Category;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AddMultipleCategoriesAction
{
    /**
     * @param string $categories - Comma separated list
     * @param User $user
     * @return Collection
     */
    public function execute(string $categories, User $user): Collection
    {
        $categories = explode(',', $categories);
        $categories = array_map('trim', $categories);

        $ids = [];
        foreach ($categories as $category) {
            $category = Category::firstOrCreate([
                'name' => $category
            ]);
            if ($category->wasRecentlyCreated) {
                $ids[] = $category->id;
            }
        }

        if ($ids) {
            $user->categories()->syncWithoutDetaching($ids);
        }

        return Category::find($ids);

    }
}
