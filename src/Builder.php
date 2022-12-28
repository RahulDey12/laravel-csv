<?php

declare(strict_types=1);

namespace Rahul900day\Csv;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;
use League\Csv\Reader;
use League\Csv\Statement;
use Rahul900day\Csv\Concerns\HasReader;
use Rahul900day\Csv\Sheet\Row;
use Traversable;
use UnitEnum;

class Builder
{
    use Macroable, Conditionable, HasReader;

    protected Statement $statement;

    protected bool|array $sanitize = false;

    public function __construct(protected Reader $reader)
    {
        $this->statement = Statement::create();
    }

    public function skip($value): static
    {
        return $this->offset($value);
    }

    public function offset(int $value): static
    {
        $this->statement = $this->statement->offset($value);

        return $this;
    }

    public function limit(int $value): static
    {
        $this->statement = $this->statement->limit($value);

        return $this;
    }

    public function forPage(int $page, int $perPage = 15): static
    {
        return $this->offset(($page - 1) * $perPage)->limit($perPage);
    }

    public function take(int $value): static
    {
        return $this->limit($value);
    }

    public function get($columns = []): Collection
    {
        return Collection::make(new Csv::$sheetClass(
            $this->statement->process($this->getReader(), $columns),
            $this->sanitize
        ));
    }

    public function lazy(int $chunkSize = 1000): LazyCollection
    {
        return LazyCollection::make(function () use ($chunkSize) {
            $page = 1;

            while (true) {
                $results = $this->forPage($page++, $chunkSize)->get();

                foreach ($results as $result) {
                    yield $result;
                }

                if ($results->count() < $chunkSize) {
                    return;
                }
            }
        });
    }

    public function chunk(int $count, callable $callback): bool
    {
        $page = 1;

        do {
            // We'll execute the query for the given page and get the results. If there are
            // no results we can just break and return from here. When there are results
            // we will call the callback with the current chunk of these results here.
            $results = $this->forPage($page, $count)->get();

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

    public function chunkMap(callable $callback, int $count = 1000): Collection
    {
        $collection = Collection::make();

        $this->chunk($count, function ($items) use ($collection, $callback) {
            $items->each(function ($item) use ($collection, $callback) {
                $collection->push($callback($item));
            });
        });

        return $collection;
    }

    public function each(callable $callback, $count = 1000): bool
    {
        return $this->chunk($count, function ($results) use ($callback) {
            foreach ($results as $key => $value) {
                if ($callback($value, $key) === false) {
                    return false;
                }
            }
        });
    }

    public function first($columns = []): ?Row
    {
        return $this->take(1)->get($columns)->first();
    }

    public function tap(callable $callback): static
    {
        $callback($this);

        return $this;
    }

    public function where(string $key, mixed $operator = null, mixed $value = null): static
    {
        $this->statement = $this->statement->where($this->operatorForWhere(...func_get_args()));

        return $this;
    }

    public function whereNull(?string $key = null): static
    {
        return $this->whereStrict($key, null);
    }

    public function whereStrict(string $key, mixed $value): static
    {
        return $this->where($key, '===', $value);
    }

    public function whereIn(string $key, Arrayable|iterable $values, bool $strict = false): static
    {
        $values = $this->getArrayableItems($values);

        $this->statement = $this->statement->where(fn ($record) => in_array(data_get($record, $key), $values, $strict));

        return $this;
    }

    public function whereInStrict(string $key, Arrayable|iterable $values): static
    {
        return $this->whereIn($key, $values, true);
    }

    public function whereBetween(string $key, Arrayable|iterable $values): static
    {
        return $this->where($key, '>=', reset($values))->where($key, '<=', end($values));
    }

    public function whereNotBetween(string $key, Arrayable|iterable $values): static
    {
        $this->statement = $this->statement->where(
            fn ($record) => data_get($record, $key) < reset($values) || data_get($record, $key) > end($values)
        );

        return $this;
    }

    public function whereNotIn(string $key, Arrayable|iterable $values, bool $strict = false): static
    {
        $values = $this->getArrayableItems($values);

        $this->statement = $this->statement->where(fn ($record) => ! in_array(data_get($record, $key), $values, $strict));

        return $this;
    }

    public function whereNotInStrict(string $key, Arrayable|iterable $values): static
    {
        return $this->whereNotIn($key, $values, true);
    }

    protected function operatorForWhere(string $key, mixed $operator = null, mixed $value = null): callable
    {
        if (func_num_args() === 2) {
            $value = $operator;

            $operator = '=';
        }

        return function ($item) use ($key, $operator, $value) {
            $retrieved = Arr::get($item, $key);

            switch ($operator) {
                default:
                case '=':
                case '==':  return $retrieved == $value;
                case '!=':
                case '<>':  return $retrieved != $value;
                case '<':   return $retrieved < $value;
                case '>':   return $retrieved > $value;
                case '<=':  return $retrieved <= $value;
                case '>=':  return $retrieved >= $value;
                case '===': return $retrieved === $value;
                case '!==': return $retrieved !== $value;
                case '<=>': return $retrieved <=> $value;
            }
        };
    }

    protected function getArrayableItems($items): array
    {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof Enumerable) {
            return $items->all();
        } elseif ($items instanceof Arrayable) {
            return $items->toArray();
        } elseif ($items instanceof Traversable) {
            return iterator_to_array($items);
        } elseif ($items instanceof Jsonable) {
            return json_decode($items->toJson(), true);
        } elseif ($items instanceof JsonSerializable) {
            return (array) $items->jsonSerialize();
        } elseif ($items instanceof UnitEnum) {
            return [$items];
        }

        return (array) $items;
    }

    public function willBeSanitized(array $sanitizers = []): static
    {
        $this->sanitize = $sanitizers;

        return $this;
    }
}
