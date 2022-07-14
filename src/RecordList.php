<?php

declare(strict_types=1);

namespace Rahul900day\Csv;

use Countable;
use Illuminate\Support\Collection;
use Iterator;
use IteratorIterator;

class RecordList extends IteratorIterator implements Countable, Iterator
{
    protected int $count = -1;

    public function __construct(protected Iterator $csv_data)
    {
        parent::__construct($this->csv_data);
    }

    public function count(): int
    {
        if($this->count === -1) {
            $this->count = iterator_count($this->csv_data);
        }

        return $this->count;
    }

    public function current(): Row
    {
        return new Row(parent::current());
    }

    public function collect(): Collection
    {
        return new Collection($this);
    }
}