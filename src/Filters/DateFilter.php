<?php

namespace Emargareten\EloquentFilters\Filters;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;

class DateFilter extends BaseFilter
{
    public function equal(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereDate($property, '=', $value);
    }

    public function notEqual(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereDate($property, '!=', $value);
    }

    public function greaterThan(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereDate($property, '>', $value);
    }

    public function lessThan(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereDate($property, '<', $value);
    }

    public function greaterThanOrEqual(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereDate($property, '>=', $value);
    }

    public function lessThanOrEqual(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->whereDate($property, '<=', $value);
    }

    /**
     * @param  iterable<int, mixed>  $values
     */
    public function between(Builder $query, string $property, iterable $values): void
    {
        $query->whereBetween($property, $values);
    }

    /**
     * @param  iterable<int, mixed>  $values
     */
    public function notBetween(Builder $query, string $property, iterable $values): void
    {
        $query->whereNotBetween($property, $values);
    }
}
