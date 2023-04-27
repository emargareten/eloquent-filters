<?php

namespace Emargareten\EloquentFilters\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseFilter
{
    public function exists(Builder $query, string $property): void
    {
        $query->whereNotNull($property);
    }

    public function notExists(Builder $query, string $property): void
    {
        $query->whereNull($property);
    }

    /**
     * @param  array<int, mixed>  $values
     */
    public function in(Builder $query, string $property, array $values): void
    {
        $query->whereIn($property, $values);
    }

    /**
     * @param  array<int, mixed>  $values
     */
    public function notIn(Builder $query, string $property, array $values): void
    {
        $query->whereNotIn($property, $values);
    }
}
