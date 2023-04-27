<?php

use Emargareten\EloquentFilters\Tests\Models\Post;

beforeEach(function () {
    Post::create(['views' => 1]);
    Post::create(['views' => 2]);
    Post::create(['views' => 3]);
    Post::create(['views' => 4]);
});

test('number:equal', function () {
    expect(Post::filter([
        [
            'property' => 'views',
            'operator' => 'equal',
            'value' => 1,
        ],
    ])->count())->toBe(1);
});

test('number:not-equal', function () {
    expect(Post::filter([
        [
            'property' => 'views',
            'operator' => 'not-equal',
            'value' => 1,
        ],
    ])->count())->toBe(3);
});

test('number:greater-than', function () {
    expect(Post::filter([
        [
            'property' => 'views',
            'operator' => 'greater-than',
            'value' => 1,
        ],
    ])->count())->toBe(3);
});

test('number:greater-than-or-equal', function () {
    expect(Post::filter([
        [
            'property' => 'views',
            'operator' => 'greater-than-or-equal',
            'value' => 1,
        ],
    ])->count())->toBe(4);
});

test('number:less-than', function () {
    expect(Post::filter([
        [
            'property' => 'views',
            'operator' => 'less-than',
            'value' => 3,
        ],
    ])->count())->toBe(2);
});

test('number:less-than-or-equal', function () {
    expect(Post::filter([
        [
            'property' => 'views',
            'operator' => 'less-than-or-equal',
            'value' => 3,
        ],
    ])->count())->toBe(3);
});

test('number:between', function () {
    expect(Post::filter([
        [
            'property' => 'views',
            'operator' => 'between',
            'value' => [1, 3],
        ],
    ])->count())->toBe(3);
});

test('number:not-between', function () {
    expect(Post::filter([
        [
            'property' => 'views',
            'operator' => 'not-between',
            'value' => [1, 3],
        ],
    ])->count())->toBe(1);
});
