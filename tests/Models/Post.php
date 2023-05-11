<?php

namespace Emargareten\EloquentFilters\Tests\Models;

use Emargareten\EloquentFilters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use Filterable;

    protected $guarded = [];

    /**
     * @var string[]
     */
    public array $filterTypes = [
        'content' => 'string',
        'is_published' => 'boolean',
        'published_at' => 'date',
        'reading_time' => 'time',
        'views' => 'number',
        'created_at' => \Emargareten\EloquentFilters\Filters\DateFilter::class,
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
