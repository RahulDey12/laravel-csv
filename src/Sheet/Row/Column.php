<?php

namespace Rahul900day\Csv\Sheet\Row;

use Illuminate\Pipeline\Pipeline;

class Column
{
    public function __construct(protected string $cell, protected bool|array $sanitize = false)
    {
    }

    public function getCell(): string
    {
        return $this->cell;
    }

    public function getValue(): ?string
    {
        if($this->sanitize !== false) {
            return  $this->getSanitizedValue($this->sanitize);
        }

        return $this->getCell();
    }

    public function getSanitizedValue(bool|array $sanitizers = []): mixed
    {
        return app(Pipeline::class)
            ->send($this->getCell())
            ->through(array_filter(
                is_array($sanitizers)
                    ? array_merge(config('csv.sanitizers'), $sanitizers)
                    : config('csv.sanitizers')
            ))
            ->thenReturn();
    }

    public function setSanitize(bool $sanitize): static
    {
        $this->sanitize = $sanitize;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->getValue();
    }
}
