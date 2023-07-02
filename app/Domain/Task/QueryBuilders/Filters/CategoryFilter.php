<?php


namespace Domain\Task\QueryBuilders\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class CategoryFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('categories', function (Builder $query) use ($value) {
            $query->where('category_id', $value);
        });
    }
}
