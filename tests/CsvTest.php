<?php

use League\Csv\Reader;
use Rahul900day\Csv\Csv;

it('can add header offset', function () {
    $offset = 2;

    $reader = mock(Reader::class)
        ->shouldReceive('setHeaderOffset')
        ->once()
        ->withArgs([$offset])
        ->andReturnSelf()
        ->getMock();

    $csv = new Csv($reader);
    $csv->setHeaderOffset($offset)->query();
});
