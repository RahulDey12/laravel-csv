<?php

declare(strict_types=1);

namespace Rahul900day\Csv;

use League\Csv\Reader;

class Csv
{
    public static string $builderClass = 'Rahul900day\\Csv\\Builder';

    public static string $sheetClass = 'Rahul900day\\Csv\\Sheet';

    public static string $rowClass = 'Rahul900day\\Csv\\Sheet\\Row';

    public static string $columnClass = 'Rahul900day\\Csv\\Sheet\\Row\\Column';

    protected int $headerOffset = 0;

    public function __construct(protected Reader $reader)
    {
    }

    public static function useBuilderClass(string $class): void
    {
        self::$builderClass = $class;
    }

    public static function useSheetClass(string $class): void
    {
        self::$sheetClass = $class;
    }

    public static function useRowClass(string $class): void
    {
        self::$rowClass = $class;
    }

    public static function useColumnClass(string $class): void
    {
        self::$columnClass = $class;
    }

    public function setHeaderOffset(int $offset): static
    {
        $this->headerOffset = $offset;

        return $this;
    }

    public function query(): Builder
    {
        return new self::$builderClass($this->setupReader());
    }

    protected function setupReader(): Reader
    {
        $this->reader->setHeaderOffset($this->headerOffset);

        return $this->reader;
    }
}
