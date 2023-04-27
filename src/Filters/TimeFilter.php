<?php

namespace Emargareten\EloquentFilters\Filters;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;

class TimeFilter extends BaseFilter
{
    public function equal(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereTime($property, '=', $value);
    }

    public function notEqual(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereTime($property, '!=', $value);
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
