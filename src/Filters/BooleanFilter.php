<?php

namespace Emargareten\EloquentFilters\Filters;

use Illuminate\Database\Eloquent\Builder;

class BooleanFilter
{
    public function exists(Builder $query, string $property): void
    {
        $query->whereNotNull($property);
    }

    public function notExists(Builder $query, string $property): void
    {
        $query->whereNull($property);
    }

    public function equal(Builder $query, string $property, mixed $value): void
    {
        $query->where($property, filter_var($value, FILTER_VALIDATE_BOOLEAN));
    }

    public function notEqual(Builder $query, string $property, mixed $value): void
    {
        $query->where($property, '!=', filter_var($value, FILTER_VALIDATE_BOOLEAN));
    }
}
