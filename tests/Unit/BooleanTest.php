<?php

use Emargareten\EloquentFilters\Tests\Models\Post;

beforeEach(function () {
    Post::create(['is_published' => true]);
    Post::create(['is_published' => false]);
    Post::create(['is_published' => false]);
});

test('boolean:equal', function () {
    expect(Post::filter([
        [
            'property' => 'is_published',
            'operator' => 'equal',
            'value' => true,
        ],
    ])->count())->toBe(1);
});

test('boolean:not-equal', function () {
    expect(Post::filter([
        [
            'property' => 'is_published',
            'operator' => 'not-equal',
            'value' => true,
        ],
    ])->count())->toBe(2);
});

test('boolean:equal with string values', function () {
    expect(Post::filter([
        [
            'property' => 'is_published',
            'operator' => 'equal',
            'value' => 'true',
        ],
        [
            'property' => 'is_published',
            'operator' => 'equal',
            'value' => '1',
        ],
        [
            'property' => 'is_published',
            'operator' => 'equal',
            'value' => 'yes',
        ],
        [
            'property' => 'is_published',
            'operator' => 'equal',
            'value' => 'on',
        ],
    ])->count())->toBe(1);
});
