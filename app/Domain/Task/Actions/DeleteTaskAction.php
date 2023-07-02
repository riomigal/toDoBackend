<?php

namespace Domain\Task\Actions;

use Domain\Task\Models\Category;
use Domain\Task\Models\Task;
use Illuminate\Support\Facades\DB;

class DeleteTaskAction
{
    public function execute(Task $task): void
    {
        try {
            DB::beginTransaction();
            $categoryIds = $task->categories()->pluck('id')->all();
            $foundIds = DB::table('category_task')->whereIn('category_id', $categoryIds)->pluck('category_id')->all();
            $categoryIds = array_diff($categoryIds, $foundIds);
            Category::where('id', $categoryIds)->delete();
            $task->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
