<?php

declare(strict_types=1);

namespace Rahul900day\Csv;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use League\Csv\Reader;
use League\Csv\Statement;

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

    public function get($columns = []): Collection
    {
        if($this->include_header) {
            $this->csv_reader->setHeaderOffset($this->header_offset);
        }

        return Collection::make(new RecordList(Statement::create()->process($this->csv_reader, $columns)));
    }

    public function lazy($chunkSize = 1000): LazyCollection
    {
        if($this->include_header) {
            $this->csv_reader->setHeaderOffset($this->header_offset);
        }

        return LazyCollection::make(function () use ($chunkSize) {
            $page = 0;

            while (true) {
                $results = Statement::create()
                    ->offset($page++ * $chunkSize)
                    ->limit($chunkSize)
                    ->process($this->csv_reader);

                $results = new RecordList($results);

                foreach ($results as $result) {
                    yield $result;
                }

                if($results->count() < $chunkSize) {
                    return;
                }
            }
        });
    }

    public function chunk()
    {

    }
}
