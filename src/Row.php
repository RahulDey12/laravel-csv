<?php

namespace Rahul900day\Csv;

use Illuminate\Support\Arr;

class Row
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

    protected function sanitizeValue(?string $value): ?string
    {
        $value = trim($value);

        return strlen($value) ? $value : null;
    }
}
