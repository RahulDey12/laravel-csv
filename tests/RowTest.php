<?php

use Rahul900day\Csv\Exceptions\ColumnDoesNotExists;
use Rahul900day\Csv\Sheet\Row;
use Rahul900day\Csv\Sheet\Row\Column;

beforeEach(function () {
    $this->row = new Row([
        'name' => 'Taylor Otwell',
        'designation' => 'Developer',
    ], false);
});

it('can give column with name', function () {
    expect($this->row->getColumn('name'))->toBeInstanceOf(Column::class)
        ->and($this->row->getColumn('designation')->getValue())->toBe('Developer');

});

it('can give column with object property', function () {
    expect($this->row->name)->toBeInstanceOf(Column::class)
        ->and($this->row->designation->getValue())->toBe('Developer');
});

it('can check for column exists', function () {
    expect($this->row->has('name'))->toBeTrue()
        ->and($this->row->has('email'))->toBeFalse();
});

it('compatible with ArrayAccess', function () {
    expect($this->row['name'])->toBe('Taylor Otwell');

    $this->row['name'] = 'Rahul Dey';
    expect($this->row->name)->toBeInstanceOf(Column::class)
        ->and($this->row->name->getValue())->toBe('Rahul Dey')
        ->and($this->row->offsetExists('name'))->toBeTrue()
        ->and($this->row->offsetExists('email'))->toBeFalse();

    unset($this->row['name']);

    expect($this->row['name'])->toBe('');
});

it('throws error if column not exits', function () {
    expect(fn() => $this->row['email'] = 'name@me.com')->toThrow(ColumnDoesNotExists::class);
});


