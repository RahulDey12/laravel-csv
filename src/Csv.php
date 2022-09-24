<?php

declare(strict_types=1);

namespace Rahul900day\Csv;

use League\Csv\Reader;
use Rahul900day\Csv\Exceptions\LogicException;

class Csv
{
    protected int $header_offset = 0;

    public function __construct(protected Reader $csv_reader)
    {
    }

    public static function fromPath(string $path): static
    {
        return new static(Reader::createFromPath($path));
    }

    public function setHeader(int $offset): static
    {
        $this->header_offset = $offset;

        return $this;
    }

    public function query(): Builder
    {
        if(! isset($this->csv_reader)) {
            new LogicException('Csv Source is Not Defied.');
        }

        $this->csv_reader->setHeaderOffset($this->header_offset);

        return new Builder($this->csv_reader);
    }
}
