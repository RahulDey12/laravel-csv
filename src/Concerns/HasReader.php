<?php

namespace Rahul900day\Csv\Concerns;

use League\Csv\Reader;

trait HasReader
{
    public function getReader(): Reader
    {
        return $this->reader;
    }

    public function setReader(Reader $reader): void
    {
        $this->reader = $reader;
    }
}
