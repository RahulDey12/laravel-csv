<?php

use League\Csv\Reader;
use Rahul900day\Csv\Concerns\HasReader;

it('can set reader', function () {
    $csv = new Csv;
    $reader = Mockery::mock(Reader::class);

    $csv->setReader($reader);

    expect($csv->reader)->toBe($reader);
});

it('can get reader', function () {
    $csv = new Csv;
    $reader = Mockery::mock(Reader::class);

    $csv->reader = $reader;

    expect($csv->getReader())->toBe($reader);
});

class Csv
{
    use HasReader;

    public Reader $reader;
}
