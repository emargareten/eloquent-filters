<?php

namespace Emargareten\EloquentFilters\Filters;

use Illuminate\Database\Eloquent\Builder;

class StringFilter extends BaseFilter
{
    public function equal(Builder $query, string $property, string $value): void
    {
        $query->where($property, $value);
    }

    public function notEqual(Builder $query, string $property, string $value): void
    {
        $query->where($property, '!=', $value);
    }

    public function contains(Builder $query, string $property, string $value): void
    {
        $query->where($property, 'LIKE', "%{$value}%");
    }

    public function notContains(Builder $query, string $property, string $value): void
    {
        $query->where($property, 'NOT LIKE', "%{$value}%");
    }

    public function startsWith(Builder $query, string $property, string $value): void
    {
        $query->where($property, 'LIKE', "{$value}%");
    }

    public function notStartsWith(Builder $query, string $property, string $value): void
    {
        $query->where($property, 'NOT LIKE', "{$value}%");
    }

    public function endsWith(Builder $query, string $property, string $value): void
    {
        $query->where($property, 'LIKE', "%{$value}");
    }

    public function notEndsWith(Builder $query, string $property, string $value): void
    {
        $query->where($property, 'NOT LIKE', "%{$value}");
    }
}
