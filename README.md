# Eloquent Filters

[![Latest Version on Packagist](https://img.shields.io/packagist/v/emargareten/eloquent-filters.svg?style=flat-square)](https://packagist.org/packages/emargareten/eloquent-filters)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Tests](https://github.com/emargareten/eloquent-filters/actions/workflows/tests.yml/badge.svg)](https://github.com/emargareten/eloquent-filters/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/emargareten/eloquent-filters.svg?style=flat-square)](https://packagist.org/packages/emargareten/eloquent-filters)

Eloquent Filterable is a package that helps you filter Laravel Eloquent models using arrays. With this package, you can easily filter Eloquent models based on different criteria and combinations of criteria.

## Requirements
This package requires PHP 8.1 or later.

## Installation

You can install the package via composer:

``` bash
composer require emargareten/eloquent-filters
```

## Usage

To get started, you need to add the `Emargareten\EloquentFilterable\Filterable` trait to your model. This trait adds a `filter` method to your model that you can use to filter your model.

Then, add a `filterTypes` property to your model that contains an array of filterable fields. The keys of the array are the names of the fields that can be filtered, and the values are the names of the [filter-type](#filter-types) that should be applied to the field.

``` php
use Emargareten\EloquentFilterable\Filterable;

class User extends Model
{
    use Filterable;
    
    public array $filterTypes = [
        'name' => 'string',
        'email' => 'string',
        'age' => 'number',
        'created_at' => 'date',
    ];
}
```

### Filter Types

The following filter types are available by default: `string`, `number`, `boolean`, `date` and `time`.

Each type has a corresponding filter class that is used to filter the field. For example, the `string` type uses the `Emargareten\EloquentFilterable\Filters\StringFilter` class.

To create your own filter types or override/extend existing types you should create a class where each method is a filter operator, the method name should be the operator name (camel case) and the method should accept 3 parameters: `$query`, `$property` and `$value`.

``` php
namespace App\Filters;

use Emargareten\EloquentFilterable\Filters\StringFilter as BaseStringFilter;

class StringFilter extends BaseStringFilter
{
    public function pattern(Builder $query, string $property, string $value): void
    {
        $query->where($property, 'REGEXP', $value);
    }

    public function notPattern(Builder $query, string $property, string $value): void
    {
        $query->where($property, 'NOT REGEXP', $value);
    }
}
```

Then register your filter class in the `filter_types` config option. (Publish the file with `php artisan vendor:publish --provider="Emargareten\EloquentFilterable\EloquentFilterableServiceProvider"`)

```diff
return [
    /*
     * The filter classes to be used for each type.
     */
    'filter_types' => [
-        'string' => \Emargareten\EloquentFilters\Filters\StringFilter::class,
+        'string' => \App\Filters\StringFilter::class,
        'number' => \Emargareten\EloquentFilters\Filters\NumberFilter::class,
        'boolean' => \Emargareten\EloquentFilters\Filters\BooleanFilter::class,
        'date' => \Emargareten\EloquentFilters\Filters\DateFilter::class,
        'time' => \Emargareten\EloquentFilters\Filters\TimeFilter::class,
    ],
];
```

### Filtering

You can filter your model by calling the `filter` method on your model and passing an array of filters.

Each filter consists of 3 elements  `property`, `operator` and `value`.

The `property` is the name of the field that should be filtered, this field has to be defined in the `filterTypes` property of your model (this prevents SQL injection). 
The `operator` is the operator that should be used to filter the field (it can be in whatever casing you want).
The `value` is the value that should be used to filter the field.

``` php
User::filter([
    [
        'property' => 'name',
        'operator' => 'equal',
        'value' => 'John',
    ],
    [
        'property' => 'age',
        'operator' => 'greater-than',
        'value' => 18,
    ],
])->get();
```

You can override the default operator type by prefixing the operator with the type and a colon (`:`). For example, if you want to use the `string` type for the age field instead of the `number` type, you can use the `string:starts-with` operator.

If you have only one filter, you can pass the filter directly to the `filter` method instead of an array of filters.

``` php
User::filter([
    'property' => 'name',
    'operator' => 'equal',
    'value' => 'John',
])->get();
```

To group filters using `OR` you can nest the filters in an additional array.

``` php
User::filter([
    [
        'property' => 'name',
        'operator' => 'equal',
        'value' => 'John',
    ],
    [
        [
            'property' => 'age',
            'operator' => 'less-than',
            'value' => 18,
        ],
        [
            'property' => 'age',
            'operator' => 'greater-than',
            'value' => 60,
        ],
    ],
])->get();
```

The above example will return all users named John that are either younger than 18 or older than 60.

#### Filtering by relationships
To filter a relationship you can use the dot notation to specify the relationship and the field that should be filtered.

``` php
User::filter([
    [
        'property' => 'posts.title',
        'operator' => 'contains',
        'value' => 'laravel',
    ],
])->get();
```

The above example will return all users that have a post with a title that contains the word laravel.

> **Note**
> You have to use the `Filterable` trait on the related model in order to filter by relationship.

### Available Operators

#### String Operators
- `exists` - The field value is not null. (you can omit the `value` parameter)
- `not-exists` - The field value is null. (you can omit the `value` parameter)
- `equal` - The field value is equal to the filter value.
- `not-equal` - The field value is not equal to the filter value.
- `starts-with` - The field value starts with the filter value.
- `not-starts-with` - The field value does not start with the filter value.
- `ends-with` - The field value ends with the filter value.
- `not-ends-with` - The field value does not end with the filter value.
- `contains` - The field value contains the filter value.
- `not-contains` - The field value does not contain the filter value.
- `in` - The field value is in the filter value. (`value` should be an array)
- `not-in` - The field value is not in the filter value. (`value` should be an array)

#### Number Operators
- `exists` - The field value is not null. (you can omit the `value` parameter)
- `not-exists` - The field value is null. (you can omit the `value` parameter)
- `equal` - The field value is equal to the filter value.
- `not-equal` - The field value is not equal to the filter value.
- `greater-than` - The field value is greater than the filter value.
- `greater-than-or-equal` - The field value is greater than or equal to the filter value.
- `less-than` - The field value is less than the filter value.
- `less-than-or-equal` - The field value is less than or equal to the filter value.
- `between` - The field value is between the filter value. (`value` should be an array)
- `not-between` - The field value is not between the filter value. (`value` should be an array)
- `in` - The field value is in the filter value. (`value` should be an array)
- `not-in` - The field value is not in the filter value. (`value` should be an array)

#### Boolean Operators
- `exists` - The field value is not null. (you can omit the `value` parameter)
- `not-exists` - The field value is null. (you can omit the `value` parameter)
- `equal` - The field value is equal to the filter value.
- `not-equal` - The field value is not equal to the filter value.

#### Date Operators
- `exists` - The field value is not null. (you can omit the `value` parameter)
- `not-exists` - The field value is null. (you can omit the `value` parameter)
- `equal` - The field date is the same date as the filter value.
- `not-equal` - The field date is not the same date as the filter value.
- `greater-than` - The field date is after the filter value.
- `greater-than-or-equal` - The field date is after or equal to the filter value.
- `less-than` - The field date is before the filter value.
- `less-than-or-equal` - The field date is before or equal to the filter value.
- `between` - The field date is between the filter value dates. (`value` should be an array)
- `not-between` - The field date is not between the filter value dates. (`value` should be an array)

#### Time Operators
- `exists` - The field value is not null. (you can omit the `value` parameter)
- `not-exists` - The field value is null. (you can omit the `value` parameter)
- `equal` - The field time is the same time as the filter value.
- `not-equal` - The field time is not the same time as the filter value.
- `greater-than` - The field time is after the filter value.
- `greater-than-or-equal` - The field time is after or equal to the filter value.
- `less-than` - The field time is before the filter value.
- `less-than-or-equal` - The field time is before or equal to the filter value.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Testing

``` bash
composer test
```

## Contributing
Contributions are welcome! If you find any bugs or issues or have a feature request, please open a new issue or submit a pull request. Before contributing, please make sure to read the [Contributing Guide](CONTRIBUTING.md).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.