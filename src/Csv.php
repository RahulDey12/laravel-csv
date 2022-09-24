<?php

declare(strict_types=1);

namespace Rahul900day\Csv;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use League\Csv\Exception;
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

    public function get($columns = []): Collection
    {
        if($this->include_header) {
            $this->csv_reader->setHeaderOffset($this->header_offset);
        }

        return Collection::make(new RecordList(Statement::create()->process($this->csv_reader, $columns)));
    }

    public function lazy(int $chunkSize = 1000): LazyCollection
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

    public function chunk(int $count, Closure $callback): bool
    {
        if($this->include_header) {
            $this->csv_reader->setHeaderOffset($this->header_offset);
        }

        $page = 1;

        do {
            // We'll execute the query for the given page and get the results. If there are
            // no results we can just break and return from here. When there are results
            // we will call the callback with the current chunk of these results here.
            $results = Statement::create()
                ->offset(($page - 1) * $count)
                ->limit($count)
                ->process($this->csv_reader);

            $results = new RecordList($results);

            $countResults = $results->count();

            if ($countResults == 0) {
                break;
            }

            // On each chunk result set, we will pass them to the callback and then let the
            // developer take care of everything within the callback, which allows us to
            // keep the memory low for spinning through large result sets for working.
            if ($callback($results, $page) === false) {
                return false;
            }

            unset($results);

            $page++;
        } while ($countResults == $count);

        return true;
    }
}
