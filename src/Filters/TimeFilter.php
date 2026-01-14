<?php

namespace Emargareten\EloquentFilters\Filters;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;

class TimeFilter
{
    public function exists(Builder $query, string $property): void
    {
        $query->whereNotNull($property);
    }

    public function notExists(Builder $query, string $property): void
    {
        $query->whereNull($property);
    }

    public function equal(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereTime($property, '=', $value);
    }

    public function notEqual(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->where(fn (Builder $query) => $query
            ->whereTime($property, '!=', $value)
            ->orWhereNull($property)
        );
    }

    public function greaterThan(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereTime($property, '>', $value);
    }

    public function lessThan(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereTime($property, '<', $value);
    }

    public function greaterThanOrEqual(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereTime($property, '>=', $value);
    }

    public function lessThanOrEqual(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereTime($property, '<=', $value);
    }
}
