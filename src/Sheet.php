<?php

declare(strict_types=1);

namespace Rahul900day\Csv;

use Countable;
use Iterator;
use IteratorIterator;
use League\Csv\TabularDataReader;
use Rahul900day\Csv\Sheet\Row;

class Sheet extends IteratorIterator implements Countable, Iterator
{
    protected int $count = -1;

    public function __construct(protected TabularDataReader $records, protected bool|array $sanitize)
    {
        parent::__construct($this->records);
    }

    public function count(): int
    {
        if ($this->count === -1) {
            $this->count = $this->records->count();
        }

        return $this->count;
    }

    public function current(): Row
    {
        return new Csv::$rowClass(parent::current(), $this->sanitize);
    }
}
