<?php

namespace Rahul900day\Csv\Sanitizers;

use Closure;

class ConvertEmptyStringToNull
{
    public function __invoke(string $cell, Closure $next): ?string
    {
        return $next(strlen($cell) ? $cell : null);
    }
}
