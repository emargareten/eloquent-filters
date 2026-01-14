<?php

use Emargareten\EloquentFilters\Tests\Models\Post;

beforeEach(function () {
    Post::create(['reading_time' => '01:00:00']);
    Post::create(['reading_time' => '02:00:00']);
    Post::create(['reading_time' => '03:00:00']);
    Post::create(['reading_time' => '04:00:00']);
});

test('time:equal', function () {
    expect(Post::filter([
        [
            'property' => 'reading_time',
            'operator' => 'equal',
            'value' => '01:00:00',
        ],
    ])->count())->toBe(1);
});

test('time:not-equal', function () {
    expect(Post::filter([
        [
            'property' => 'reading_time',
            'operator' => 'not-equal',
            'value' => '01:00:00',
        ],
    ])->count())->toBe(3);
});

test('time:not-equal includes null values', function () {
    Post::create(['reading_time' => null]);

    expect(Post::filter([
        [
            'property' => 'reading_time',
            'operator' => 'not-equal',
            'value' => '01:00:00',
        ],
    ])->count())->toBe(4);
});

test('time:greater-than', function () {
    expect(Post::filter([
        [
            'property' => 'reading_time',
            'operator' => 'greater-than',
            'value' => '02:00:00',
        ],
    ])->count())->toBe(2);
});

test('time:greater-than-or-equal', function () {
    expect(Post::filter([
        [
            'property' => 'reading_time',
            'operator' => 'greater-than-or-equal',
            'value' => '02:00:00',
        ],
    ])->count())->toBe(3);
});

test('time:less-than', function () {
    expect(Post::filter([
        [
            'property' => 'reading_time',
            'operator' => 'less-than',
            'value' => '02:00:00',
        ],
    ])->count())->toBe(1);
});

test('time:less-than-or-equal', function () {
    expect(Post::filter([
        [
            'property' => 'reading_time',
            'operator' => 'less-than-or-equal',
            'value' => '02:00:00',
        ],
    ])->count())->toBe(2);
});
