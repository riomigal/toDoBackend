<?php

namespace Domain\Task\QueryBuilders;


use Domain\Task\Models\Task;
use Domain\Task\QueryBuilders\Filters\CategoryFilter;
use Domain\Task\QueryBuilders\Filters\SearchFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskQuery extends QueryBuilder
{
    public function __construct(?Request $request = null)
    {
        parent::__construct($this->subject(), $request);
        $this->latest()->
        with(['user', 'priority', 'categories'])
            ->allowedFilters([
                'name', 'description', 'completed', 'priority_id',
                AllowedFilter::custom('category', new CategoryFilter),
                AllowedFilter::custom('search', new SearchFilter(), 'name,description')
            ])
            ->allowedSorts(['priority_id', 'created_at'])
            ->allowedIncludes(['priority', 'categories']);
    }

    protected function subject(): Builder
    {
        return Task::where('user_id', auth()->id());
    }
}
