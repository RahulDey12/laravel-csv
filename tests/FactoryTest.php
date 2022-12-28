<?php

use League\Csv\Reader;
use Rahul900day\Csv\Csv;
use Rahul900day\Csv\Factory;

beforeEach(function () {
    $this->factory = new Factory();
});

it('can create from file object', function () {
    $file_obj = mock(SplFileObject::class, ["php://memory"])
        ->shouldReceive('getCsvControl')
        ->andReturn(',', '"', "\\")
        ->getMock();

    mock('alias:'.Reader::class)
        ->shouldReceive('createFromFileObject')
        ->once()
        ->withArgs([$file_obj, 'r'])
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
