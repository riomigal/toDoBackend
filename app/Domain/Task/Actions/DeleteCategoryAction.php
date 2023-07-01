<?php

namespace Domain\Task\Actions;

use Domain\Task\Models\Category;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteCategoryAction
{
    public function execute(Category $category, User $user): bool
    {
        $id = $category->id;
        $user->categories()->detach([$category->id]);

        // Delete Category if none more present in Pivot table
        $count = DB::table('category_user')->where('category_id', $id)->count();
        if ($count < 1) {
            $category->delete();
        }

        return true;
    }
}
