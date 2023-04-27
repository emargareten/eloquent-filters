<?php

return [
    /*
     * The filter classes to be used for each type.
     */
    'filter_types' => [
        'string' => \Emargareten\EloquentFilters\Filters\StringFilter::class,
        'number' => \Emargareten\EloquentFilters\Filters\NumberFilter::class,
        'boolean' => \Emargareten\EloquentFilters\Filters\BooleanFilter::class,
        'date' => \Emargareten\EloquentFilters\Filters\DateFilter::class,
        'time' => \Emargareten\EloquentFilters\Filters\TimeFilter::class,
    ],
];
