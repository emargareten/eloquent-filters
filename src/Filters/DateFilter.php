<?php

namespace Emargareten\EloquentFilters\Filters;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;

class DateFilter
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
        $query->whereDate($property, '=', $value);
    }

    public function notEqual(Builder $query, string $property, string|DateTimeInterface $value): void
    {
        $query->where(fn (Builder $query) => $query
            ->whereDate($property, '!=', $value)
            ->orWhereNull($property)
        );
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
        $valuesArray = is_array($values) ? $values : iterator_to_array($values);

        if (count($valuesArray) === 1) {
            $query->whereDate($property, '>=', $valuesArray[0]);
        } else {
            $query->whereBetween($property, $values);
        }
    }

    /**
     * @param  iterable<int, mixed>  $values
     */
    public function notBetween(Builder $query, string $property, iterable $values): void
    {
        $valuesArray = is_array($values) ? $values : iterator_to_array($values);

        if (count($valuesArray) === 1) {
            $query->whereDate($property, '<', $valuesArray[0]);
        } else {
            $query->whereNotBetween($property, $values);
        }
    }
}
