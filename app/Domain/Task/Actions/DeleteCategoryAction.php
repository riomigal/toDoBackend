<?php

namespace Domain\Task\Actions;

use Domain\Task\Models\Category;
use Domain\Task\Models\Task;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteCategoryAction
{
    public function execute(Category $category, User $user): void
    {
        $id = $category->id;
        try {
            DB::beginTransaction();
            // Detach Categories from user and tasks
            $user->categories()->detach([$category->id]);
            $category->tasks()->where('user_id', $user->id)->each(function (Task $task) use ($category) {
                $task->categories()->detach([$category->id]);
            });

            // Delete Category if none more present in Pivot table
            $count = DB::table('category_user')->where('category_id', $id)->count();

            if ($count < 1) {
                $category->delete();
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
