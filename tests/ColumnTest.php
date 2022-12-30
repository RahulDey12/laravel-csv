<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Rahul900day\Csv\Sanitizers\ConvertEmptyStringToNull;
use Rahul900day\Csv\Sanitizers\TrimString;
use Rahul900day\Csv\Sheet\Row\Column;

it('can give cell value', function () {
    $column = new Column('Hello');

    expect($column->getValue())->toBe('Hello');
});

it('can give sanitized value', function () {
    Config::set('csv.sanitizers', [
        TrimString::class,
    ]);

    $string_with_leading_spaces = '   Hello World';
    $column = new Column($string_with_leading_spaces);

    expect($column->getValue())->toBe($string_with_leading_spaces)
        ->and($column->getSanitizedValue())->toBe(trim($string_with_leading_spaces))
        ->and($column->getSanitizedValue(true))->toBe(trim($string_with_leading_spaces))
        ->and($column->getSanitizedValue([
            fn($cell, $next) => $next((string)Str::of($cell)->snake()),
            fn($cell, $next) => $next((string)Str::of($cell)->lower()),
        ]))->toBe('hello_world');
});

it('can give sanitized value by default', function () {
    Config::set('csv.sanitizers', [
        TrimString::class,
        ConvertEmptyStringToNull::class,
    ]);

    $string_with_only_spaces = '   ';
    $column = new Column($string_with_only_spaces);

    expect($column->getValue())->toBe($string_with_only_spaces);

    $column->setSanitize(true);
    expect($column->getValue())->toBeNull();
});

it('can be string casted', function () {
    $column = (string) new Column('Hello');

    expect($column)->toBe('Hello');
});
