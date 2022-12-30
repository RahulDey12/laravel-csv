<?php

use League\Csv\Reader;
use League\Csv\Statement;
use Rahul900day\Csv\Sheet;
use Rahul900day\Csv\Sheet\Row;

beforeEach(function () {
    $statement = app(Statement::class);
    $content = <<<EOF
name,designation
Taylor Otwell,Developer
Rahul Dey,Developer
EOF;
    $reader = Reader::createFromString($content)->setHeaderOffset(0);
    $this->sheet = new Sheet($statement->process($reader), false);
});

it('can be counted', function () {
    expect($this->sheet)->toHaveCount(2);
})->skip();

it('can be iterated with Row class', function () {
    foreach ($this->sheet as $row) {
        expect($row)->toBeInstanceOf(Row::class);
    }
})->skip();
