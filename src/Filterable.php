<?php

namespace Emargareten\EloquentFilters;

use Closure;
use Emargareten\EloquentFilters\Exceptions\FilterParameterException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait Filterable
{
    /**
     * @param  array<int|string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters = []): void
    {
        if (empty($filters)) {
            return;
        }

        if (Arr::isAssoc($filters)) {
            $filters = [$filters];
        }

        foreach ($filters as $filter) {

            if (Arr::isAssoc($filter)) {
                // Single filter - not in a group
                $this->applyFilter($query, $filter);

                continue;
            }

            // Grouped filters using OR
            $query->where(function (Builder $query) use ($filter) {
                foreach ($filter as $nestedFilter) {
                    $query->orWhere(fn (Builder $query) => $this->applyFilter($query, $nestedFilter));
                }
            });
        }
    }

    public function applyFilter(Builder $query, mixed $filter, ?string $relation = null): void
    {
        if (! isset($filter['property'])) {
            throw new FilterParameterException('Filter property is not set');
        }

        $property = $filter['property'];
        $operator = $filter['operator'] ?? 'equal';
        $value = $filter['value'] ?? null;
        $negate = filter_var($filter['negate'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $model = $relation ? $this->{$relation}()->getRelated() : $this;

        $customFilter = 'filter'.Str::studly($property);

        if (method_exists($model, $customFilter)) {
            $model->{$customFilter}($query, $operator, $value);

            return;
        }

        if (str_contains($property, '.')) {
            // Filter by relation
            $query->whereRelation(
                Str::before($property, '.'),
                fn (Builder $query) => $model->applyFilter(
                    $query,
                    Arr::set($filter, 'property', Str::after($property, '.')),
                    Str::before($property, '.'),
                )
            );

            return;
        }

        if (is_array($operator)) {
            // Filter by relation - multiple filters in single relation model

            // Filter relationship absence
            if ($negate) {
                $query->whereDoesntHave(
                    Str::after($property, '!'),
                    fn (Builder $query) => $query->filter($operator) // @phpstan-ignore-line
                );

                return;
            }

            // Filter relationship existence
            $query->whereHas($property, fn (Builder $query) => $query->filter($operator)); // @phpstan-ignore-line

            return;
        }

        $filterTypes = $model->filterTypes ?? [];

        if (! in_array($property, array_keys($filterTypes))) {
            throw new FilterParameterException("Property $property is not defined in the filterTypes array in model ".$model::class);
        }

        $type = str_contains($operator, ':')
            ? Str::before($operator, ':')
            : $filterTypes[$property] ?? 'text';

        $property = $model->getTable().'.'.$property;

        $closure = $this->getFilterClosure($type, Str::after($operator, ':'));

        $closure($query, $property, $value);
    }

    protected function getFilterClosure(string $type, string $operator): Closure
    {
        $class = $this->getFilterClass($type);

        $method = Str::camel($operator);

        if (! method_exists($class, $method)) {
            throw new FilterParameterException("Unknown filter method `$method` in $class");
        }

        return app($class)->$method(...);
    }

    protected function getFilterClass(string $type): string
    {
        if (class_exists($type)) {
            return $type;
        }

        $types = config('eloquent-filters.filter_types');

        if (! isset($types[$type])) {
            throw new FilterParameterException("Unknown filter type $type");
        }

        return $types[$type];
    }
}
