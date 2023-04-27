<?php

use Emargareten\EloquentFilters\Tests\Models\Post;

beforeEach(function () {
    Post::create(['content' => 'Lorem ipsum dolor sit amet.']);
    Post::create(['content' => 'consectetuer adipiscing elit.']);
    Post::create(['content' => 'Quisque ante felis']);
});

test('string:exists', function () {
    Post::create(['content' => null]);

    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'exists',
        ],
    ])->count())->toBe(3);
});

test('string:not-exists', function () {
    Post::create(['content' => null]);

    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'not-exists',
        ],
    ])->count())->toBe(1);
});

test('string:in', function () {
    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'in',
            'value' => ['Lorem ipsum dolor sit amet.', 'consectetuer adipiscing elit.'],
        ],
    ])->count())->toBe(2);
});

test('string:not-in', function () {
    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'not-in',
            'value' => ['Lorem ipsum dolor sit amet.', 'consectetuer adipiscing elit.'],
        ],
    ])->count())->toBe(1);
});

test('string:equal', function () {
    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'equal',
            'value' => 'Lorem ipsum dolor sit amet.',
        ],
    ])->count())->toBe(1);
});

test('string:not-equal', function () {
    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'not-equal',
            'value' => 'Lorem ipsum dolor sit amet.',
        ],
    ])->count())->toBe(2);
});

test('string:contains', function () {
    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'contains',
            'value' => 'Lorem',
        ],
    ])->count())->toBe(1);
});

test('string:not-contains', function () {
    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'not-contains',
            'value' => 'Lorem',
        ],
    ])->count())->toBe(2);
});

test('string:starts-with', function () {
    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'starts-with',
            'value' => 'Lorem',
        ],
    ])->count())->toBe(1);
});

test('string:not-starts-with', function () {
    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'not-starts-with',
            'value' => 'Lorem',
        ],
    ])->count())->toBe(2);
});

test('string:ends-with', function () {
    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'ends-with',
            'value' => 'amet.',
        ],
    ])->count())->toBe(1);
});

test('string:not-ends-with', function () {
    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'not-ends-with',
            'value' => 'amet.',
        ],
    ])->count())->toBe(2);
});
