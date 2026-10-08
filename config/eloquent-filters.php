<?php

use Emargareten\EloquentFilters\Filters\BooleanFilter;
use Emargareten\EloquentFilters\Filters\DateFilter;
use Emargareten\EloquentFilters\Filters\NumberFilter;
use Emargareten\EloquentFilters\Filters\StringFilter;
use Emargareten\EloquentFilters\Filters\TimeFilter;

return [
    /*
     * The filter classes to be used for each type.
     */
    'filter_types' => [
        'string' => StringFilter::class,
        'number' => NumberFilter::class,
        'boolean' => BooleanFilter::class,
        'date' => DateFilter::class,
        'time' => TimeFilter::class,
    ],
];
