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

    mock('alias:'.Reader::class)
        ->shouldReceive('createFromPath')
        ->once()
        ->withArgs([Storage::disk('private')->path($path), 'r'])
        ->andReturnSelf();

    $csv = $this->factory->fromDisk('private', $path);

    expect($csv)->toBeInstanceOf(Csv::class);

    expect($csv->getReader())->toBeInstanceOf(Reader::class);
});

it('can create from file object', function () {
    $file_obj = new SplFileObject('php://memory');

    mock('alias:'.Reader::class)
        ->shouldReceive('createFromFileObject')
        ->once()
        ->withArgs([$file_obj])
        ->andReturnSelf();

    $csv = $this->factory->fromFileObject($file_obj);

    expect($csv)->toBeInstanceOf(Csv::class);

    expect($csv->getReader())->toBeInstanceOf(Reader::class);
});

it('can create from path', function () {
    $file_name = 'test.csv';

    mock('alias:'.Reader::class)
        ->shouldReceive('createFromPath')
        ->once()
        ->withArgs([$file_name, 'r'])
        ->andReturnSelf();

    $csv = $this->factory->fromPath($file_name);

    expect($csv)->toBeInstanceOf(Csv::class);

    expect($csv->getReader())->toBeInstanceOf(Reader::class);
});

it('can create from file stream', function () {
    $fake_resource = '';

    mock('alias:'.Reader::class)
        ->shouldReceive('createFromStream')
        ->once()
        ->withArgs([$fake_resource])
        ->andReturnSelf();

    $csv = $this->factory->fromStream($fake_resource);

    expect($csv)->toBeInstanceOf(Csv::class);

    expect($csv->getReader())->toBeInstanceOf(Reader::class);
});

it('can create from string', function () {
    $content = '';

    mock('alias:'.Reader::class)
        ->shouldReceive('createFromString')
        ->once()
        ->withArgs([$content])
        ->andReturnSelf();

    $csv = $this->factory->fromString($content);

    expect($csv)->toBeInstanceOf(Csv::class);

    expect($csv->getReader())->toBeInstanceOf(Reader::class);
});
