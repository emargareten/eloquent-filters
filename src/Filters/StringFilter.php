<?php

namespace Emargareten\EloquentFilters\Filters;

use Illuminate\Database\Eloquent\Builder;

class StringFilter
{
    public function exists(Builder $query, string $property): void
    {
        $query->whereNotNull($property);
    }

    public function notExists(Builder $query, string $property): void
    {
        $query->whereNull($property);
    }

    public function equal(Builder $query, string $property, string $value): void
    {
        $query->where($property, $value);
    }

    public function notEqual(Builder $query, string $property, string $value): void
    {
        $query->where(fn (Builder $query) => $query
            ->where($property, '!=', $value)
            ->orWhereNull($property)
        );
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
