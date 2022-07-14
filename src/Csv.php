<?php

declare(strict_types=1);

namespace Rahul900day\Csv;

use Illuminate\Support\Collection;
use League\Csv\Reader;

class Csv
{
    protected Reader $csv_reader;

    protected int $header_offset = 0;

    protected bool $include_header = true;

    public function fromPath(string $path): static
    {
        $this->csv_reader = Reader::createFromPath($path);

        return $this;
    }

    public function includeHeader(bool $include): static
    {
        $this->include_header = $include;

        return $this;
    }

    public function setHeader(int $offset): static
    {
        $this->header_offset = $offset;

        return $this;
    }

    public function getRecords(): RecordList
    {
        if(! isset($this->csv_reader)) {
            throw new \Exception('No Reader Specified.');
        }

        if($this->include_header) {
            $this->csv_reader->setHeaderOffset($this->header_offset);
        }

        return new RecordList($this->csv_reader->getRecords());
    }
}