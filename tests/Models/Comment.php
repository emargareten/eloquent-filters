<?php

namespace Emargareten\EloquentFilters\Tests\Models;

use Emargareten\EloquentFilters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use Filterable;

    protected $guarded = [];

    /**
     * @var string[]
     */
    public array $filterTypes = [
        'body' => 'string',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
