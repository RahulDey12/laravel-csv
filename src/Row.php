<?php

namespace Rahul900day\Csv;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Row implements ArrayAccess
{
    public function __construct(protected array $record)
    {
    }

    public function getNthColumn(int $column, bool $sanitize = true)
    {
        $value = Arr::get(array_values($this->record), $column);

        return $sanitize ? $this->sanitizeValue($value) : $value;
    }

    public function getColumn(string $column, bool $sanitize = true): ?string
    {
        $value = Arr::get($this->record, $column);

        return $sanitize ? $this->sanitizeValue($value) : $value;
    }

    public function __get(string $name): ?string
    {
        return $this->getColumn($name);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->record);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet(mixed $offset): ?string
    {
        return $this->getColumn($offset, false);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->validateKeyExists($offset, "Unable set, column does not exists in the column list.");

        Arr::set($this->record, $offset, (string) $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->validateKeyExists($offset, "Unable unset, column does not exists in the column list.");

        Arr::set($this->record, $offset, "");
    }

    protected function sanitizeValue(?string $value): ?string
    {
        $value = trim($value);

        return strlen($value) ? $value : null;
    }

    protected function validateKeyExists(string $key, ?string $message = null): void
    {
        if($this->has($key)) {
           return;
        }

        throw new \Exception($message ?? "Column Does Not Exists");
    }
}
