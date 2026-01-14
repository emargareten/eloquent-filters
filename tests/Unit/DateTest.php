<?php

use Emargareten\EloquentFilters\Tests\Models\Post;

beforeEach(function () {
    Post::create(['published_at' => now()->subDays(1)]);
    Post::create(['published_at' => now()->subDays(2)]);
    Post::create(['published_at' => now()->subDays(3)]);
    Post::create(['published_at' => now()->subDays(4)]);
});

test('date:equal', function () {
    expect(Post::filter([
        [
            'property' => 'published_at',
            'operator' => 'equal',
            'value' => now()->subDays(1),
        ],
    ])->count())->toBe(1);
});

test('date:not-equal', function () {
    expect(Post::filter([
        [
            'property' => 'published_at',
            'operator' => 'not-equal',
            'value' => now()->subDays(1),
        ],
    ])->count())->toBe(3);
});

test('date:not-equal includes null values', function () {
    Post::create(['published_at' => null]);

    expect(Post::filter([
        [
            'property' => 'published_at',
            'operator' => 'not-equal',
            'value' => now()->subDays(1),
        ],
    ])->count())->toBe(4);
});

test('date:greater-than', function () {
    expect(Post::filter([
        [
            'property' => 'published_at',
            'operator' => 'greater-than',
            'value' => now()->subDays(2),
        ],
    ])->count())->toBe(1);
});

test('date:greater-than-or-equal', function () {
    expect(Post::filter([
        [
            'property' => 'published_at',
            'operator' => 'greater-than-or-equal',
            'value' => now()->subDays(2),
        ],
    ])->count())->toBe(2);
});

test('date:less-than', function () {
    expect(Post::filter([
        [
            'property' => 'published_at',
            'operator' => 'less-than',
            'value' => now()->subDays(2),
        ],
    ])->count())->toBe(2);
});

test('date:less-than-or-equal', function () {
    expect(Post::filter([
        [
            'property' => 'published_at',
            'operator' => 'less-than-or-equal',
            'value' => now()->subDays(2),
        ],
    ])->count())->toBe(3);
});

test('date:between', function () {
    expect(Post::filter([
        [
            'property' => 'published_at',
            'operator' => 'between',
            'value' => [
                now()->subDays(4),
                now()->subDays(2),
            ],
        ],
    ])->count())->toBe(3);
});

test('date:between with one element in array acts like greater-than-or-equal', function () {
    expect(Post::filter([
        [
            'property' => 'published_at',
            'operator' => 'between',
            'value' => [
                now()->subDays(3),
            ],
        ],
    ])->count())->toBe(3);
});

test('date:not-between', function () {
    expect(Post::filter([
        [
            'property' => 'published_at',
            'operator' => 'not-between',
            'value' => [
                now()->subDays(4),
                now()->subDays(2),
            ],
        ],
    ])->count())->toBe(1);
});

test('date:not-between with one element in array acts like less-than', function () {
    expect(Post::filter([
        [
            'property' => 'published_at',
            'operator' => 'not-between',
            'value' => [
                now()->subDays(3),
            ],
        ],
    ])->count())->toBe(1);
});
