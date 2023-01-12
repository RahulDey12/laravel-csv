<?php

use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Rahul900day\Csv\Csv;
use Rahul900day\Csv\Factory;

beforeEach(function () {
    $this->factory = new Factory();
});

it('can create from disk', function () {
    $path = 'test/abc.csv';
    Storage::fake('private');
    Storage::disk('private')->put($path, <<<'EOF'
name,designation
Taylor Otwell,Developer
Rahul Dey,Developer
EOF);

    $csv = $this->factory->fromDisk('private', $path);
    expect($csv)->toBeInstanceOf(Csv::class);

    $reader = $csv->getReader();
    expect($reader)->toBeInstanceOf(Reader::class)
        ->toHaveCount(3);
});

it('can create from file object', function () {
    $file_obj = new SplFileObject(__DIR__.'/files/foo.csv');

    $csv = $this->factory->fromFileObject($file_obj);
    expect($csv)->toBeInstanceOf(Csv::class);

    $reader = $csv->getReader();
    expect($reader)->toBeInstanceOf(Reader::class)
        ->toHaveCount(3);
});

it('can create from path', function () {
    $file_name = __DIR__.'/files/foo.csv';

    $csv = $this->factory->fromPath($file_name);
    expect($csv)->toBeInstanceOf(Csv::class);

    $reader = $csv->getReader();
    expect($reader)->toBeInstanceOf(Reader::class)
        ->toHaveCount(3);
});

it('can create from file stream', function () {
    $csv = $this->factory->fromStream(fopen(__DIR__.'/files/foo.csv', 'r'));
    expect($csv)->toBeInstanceOf(Csv::class);

    $reader = $csv->getReader();
    expect($reader)->toBeInstanceOf(Reader::class)
        ->toHaveCount(3);
});

it('can create from string', function () {
    $content = <<<'EOF'
name,designation
Taylor Otwell,Developer
Rahul Dey,Developer
EOF;

    $csv = $this->factory->fromString($content);
    expect($csv)->toBeInstanceOf(Csv::class);

    $reader = $csv->getReader();
    expect($reader)->toBeInstanceOf(Reader::class)
        ->toHaveCount(3);
});
