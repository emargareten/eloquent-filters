<?php

use Emargareten\EloquentFilters\Exceptions\FilterParameterException;
use Emargareten\EloquentFilters\Tests\Models\Comment;
use Emargareten\EloquentFilters\Tests\Models\Post;

test('can pass multiple filters as nested array', function () {
    Post::create(['content' => 'Lorem ipsum dolor sit amet.']);
    Post::create(['content' => 'Ipsum dolor sit amet.']);
    Post::create(['content' => null]);

    expect(Post::filter([
        [
            'property' => 'content',
            'operator' => 'exists',
        ],
        [
            'property' => 'content',
            'operator' => 'contains',
            'value' => 'Lorem',
        ],
    ])->count())->toBe(1);
});

test('can pass single filter as top level array', function () {
    Post::create(['content' => 'Lorem ipsum dolor sit amet.']);
    Post::create(['content' => null]);

    expect(Post::filter([
        'property' => 'content',
        'operator' => 'exists',
    ])->count())->toBe(1);
});

test('missing operator falls back to string:equal', function () {
    Post::create(['content' => 'Lorem ipsum dolor sit amet.']);
    Post::create(['content' => 'Ipsum dolor sit amet.']);

    expect(Post::filter([
        'property' => 'content',
        'value' => 'Lorem ipsum dolor sit amet.',
    ])->count())->toBe(1);
});

/** @phpstan-ignore-next-line  */
test('missing parameters exception', function () {
    Post::create(['content' => 'Lorem ipsum dolor sit amet.']);

    Post::filter([
        'operator' => 'contains',
        'value' => 'Lorem',
    ])->count();
})->throws(FilterParameterException::class);

test('type prefix overrides defined type', function () {
    Post::create(['content' => '500']);
    Post::create(['content' => '1500']);

    expect(Post::filter([
        'property' => 'content',
        'operator' => 'number:between',
        'value' => [1000, 2000],
    ])->count())->toBe(1);
});

test('can use fqcn in filterTypes', function () {
    Post::create(['created_at' => '2020-01-01']);
    Post::create(['created_at' => '2021-01-01']);

    expect(Post::filter([
        'property' => 'created_at',
        'operator' => 'between',
        'value' => ['2020-01-01', '2020-12-31'],
    ])->count())->toBe(1);
});

test('can apply filters on relations using dot notation', function () {
    $post = Post::create(['content' => 'Lorem ipsum dolor sit amet.']);
    $post->comments()->create(['body' => 'Lorem ipsum dolor sit amet.']);

    $post2 = Post::create(['content' => 'Ipsum dolor sit amet.']);
    $post2->comments()->create(['body' => 'Ipsum dolor sit amet.']);

    expect(Post::filter([
        'property' => 'comments.body',
        'operator' => 'contains',
        'value' => 'Lorem',
    ])->count())->toBe(1)
        ->and(Comment::filter([
            'property' => 'post.content',
            'operator' => 'contains',
            'value' => 'Lorem',
        ])->count())
        ->toBe(1);
});

test('can apply filters on relations using array', function () {
    $post = Post::create(['content' => 'Lorem ipsum dolor sit amet.']);
    $post->comments()->create([
        'body' => 'Lorem ipsum dolor sit amet.',
        'created_at' => '2020-01-01 00:00:00',
    ]);

    $post2 = Post::create(['content' => 'Lorem ipsum dolor sit amet.']);
    $post2->comments()->create([
        'body' => 'Lorem ipsum dolor sit amet.',
        'created_at' => '2021-01-01 00:00:00',
    ]);

    $post3 = Post::create(['content' => 'Ipsum dolor sit amet.']);
    $post3->comments()->create([
        'body' => 'Ipsum dolor sit amet.',
    ]);

    expect(Post::filter([
        'property' => 'comments',
        'operator' => [
            'property' => 'body',
            'operator' => 'contains',
            'value' => 'Lorem',
        ],
    ])->count())->toBe(2)
        ->and(Post::filter([
            'property' => 'comments',
            'operator' => [
                [
                    'property' => 'body',
                    'operator' => 'contains',
                    'value' => 'Lorem',
                ],
                [
                    'property' => 'created_at',
                    'operator' => 'date:greater-than-or-equal',
                    'value' => '2021-01-01',
                ],
            ],
        ])->count())
        ->toBe(1);
});

test('can apply negative filters on relations using array', function () {
    $post = Post::create(['content' => 'Lorem ipsum dolor sit amet.']);
    $post->comments()->create([
        'body' => 'Lorem ipsum dolor sit amet.',
        'created_at' => '2020-01-01 00:00:00',
    ]);

    $post2 = Post::create(['content' => 'Lorem ipsum dolor sit amet.']);
    $post2->comments()->create([
        'body' => 'Lorem ipsum dolor sit amet.',
        'created_at' => '2021-01-01 00:00:00',
    ]);

    $post3 = Post::create(['content' => 'Ipsum dolor sit amet.']);
    $post3->comments()->create([
        'body' => 'Ipsum dolor sit amet.',
    ]);

    expect(Post::filter([
        'property' => 'comments',
        'operator' => [
            'property' => 'body',
            'operator' => 'contains',
            'value' => 'Lorem',
        ],
        'negate' => true,
    ])->count())->toBe(1)
        ->and(Post::filter([
            'property' => 'comments',
            'operator' => [
                [
                    'property' => 'body',
                    'operator' => 'contains',
                    'value' => 'Lorem',
                ],
                [
                    'property' => 'created_at',
                    'operator' => 'date:greater-than-or-equal',
                    'value' => '2021-01-01',
                ],
            ],
            'negate' => true,
        ])->count())
        ->toBe(2);
});

test('dynamic filter on model', function () {
    Post::create(['views' => 10]);
    Post::create(['views' => 20]);
    Post::create(['views' => 30]);

    expect(Post::filter([
        'property' => 'has-twenty-views',
    ])->count())->toBe(2);
});

test('dynamic filter with value and operator on model', function () {
    Post::create(['views' => 10]);
    Post::create(['views' => 20]);
    Post::create(['views' => 30]);

    expect(Post::filter([
        'property' => 'has-views',
        'operator' => '>=',
        'value' => 20,
    ])->count())->toBe(2);
});

test('dynamic filter on relation model', function () {
    Post::create(['views' => 10])->comments()->create();
    Post::create(['views' => 20])->comments()->create();

    expect(Comment::filter([
        'property' => 'post.has-twenty-views',
    ])->count())->toBe(1);
});

test('dynamic filter on nested relation model', function () {
    Post::create(['views' => 10])->comments()->create();
    Post::create(['views' => 20])->comments()->create();

    expect(Comment::filter([
        'property' => 'post.comments.post.has-twenty-views',
    ])->count())->toBe(1);
});
