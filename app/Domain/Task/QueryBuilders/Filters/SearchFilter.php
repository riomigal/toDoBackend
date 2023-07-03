<?php


namespace Domain\Task\QueryBuilders\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $properties = explode(',', $property);
        $query->where(function ($query) use ($properties, $value) {
            collect($properties)->each(function ($field, $index) use ($query, $value) {
                $relation = explode('.', $field);
                if (count($relation) > 1) {
                    $field = array_pop($relation);
                    $relation = implode('.', $relation);
                    if ($index == 0) {
                        $query->whereHas($relation, function ($query) use ($field, $value) {
                            $query->where($field, 'LIKE', "%$value%");
                        });
                    } else {
                        $query->orWhereHas($relation, function ($query) use ($field, $value) {
                            $query->where($field, 'LIKE', "%$value%");
                        });
                    }
                } else {
                    if ($index == 0) {
                        $field = $query->getQuery()->from . '.' . $relation[0];
                        $query->where($field, 'LIKE', "%$value%");
                    } else {
                        $query->orWhere($field, 'LIKE', "%$value%");
                    }
                }
            });
        });
    }
}
